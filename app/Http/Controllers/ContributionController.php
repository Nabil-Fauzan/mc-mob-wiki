<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use App\Models\MobContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContributionController extends Controller
{
    public function index()
    {
        $contributions = MobContribution::with(['mob', 'user'])->where('status', 'pending')->latest()->get();
        return view('admin.contributions.index', compact('contributions'));
    }

    public function store(Request $request, Mob $mob)
    {
        $request->validate([
            'field' => 'required|string|in:description,health,damage,behavior',
            'proposed_value' => 'required|string|min:3',
        ]);

        $aiResult = app(\App\Http\Controllers\OracleController::class)->moderateContent(
            $mob->name, 
            $request->field, 
            $request->proposed_value
        );

        MobContribution::create([
            'mob_id' => $mob->id,
            'user_id' => Auth::id(),
            'field' => $request->field,
            'proposed_value' => $request->proposed_value,
            'status' => 'pending',
            'ai_assessment' => $aiResult['assessment'] ?? null,
            'ai_trust_score' => $aiResult['score'] ?? null,
        ]);

        return back()->with('success', 'Your contribution has been submitted for review! Thank you.');
    }

    public function approve(Request $request, MobContribution $contribution)
    {
        // Update the mob and track revision
        $mob = $contribution->mob;
        $field = $contribution->field;
        $oldValue = $mob->$field;

        \App\Models\MobRevision::create([
            'mob_id' => $mob->id,
            'user_id' => $contribution->user_id, // The contributor gets credit for the change
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $contribution->proposed_value,
        ]);

        $mob->$field = $contribution->proposed_value;
        $mob->save();

        // Update the contribution
        $contribution->update([
            'status' => 'approved',
            'admin_id' => Auth::id()
        ]);

        // Award XP to the user
        $user = $contribution->user;
        
        // Custom leveling XP award logic
        $xpToAward = 50; // Default
        if ($user->level <= 15) {
            $xpToAward = 250;
        } elseif ($user->level <= 30) {
            $xpToAward = 100;
        }
        
        $user->addXp($xpToAward);
        
        // Award Contributor achievement if not already earned
        $achievement = \App\Models\Achievement::where('name', 'Contributor')->first();
        if ($achievement && !$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
            $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
        }

        return back()->with('success', 'Contribution approved and applied.');
    }

    public function reject(Request $request, MobContribution $contribution)
    {
        $contribution->update([
            'status' => 'rejected',
            'admin_id' => Auth::id()
        ]);

        return back()->with('success', 'Contribution rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'contribution_ids' => 'required|array',
            'contribution_ids.*' => 'exists:mob_contributions,id',
        ]);

        $contributions = MobContribution::whereIn('id', $request->contribution_ids)
            ->where('status', 'pending')
            ->get();

        $count = 0;
        foreach ($contributions as $contribution) {
            // Update the mob and track revision
            $mob = $contribution->mob;
            $field = $contribution->field;
            $oldValue = $mob->$field;

            \App\Models\MobRevision::create([
                'mob_id' => $mob->id,
                'user_id' => $contribution->user_id,
                'field' => $field,
                'old_value' => $oldValue,
                'new_value' => $contribution->proposed_value,
            ]);

            $mob->$field = $contribution->proposed_value;
            $mob->save();

            // Update the contribution
            $contribution->update([
                'status' => 'approved',
                'admin_id' => Auth::id()
            ]);

            // Award XP to the user
            $user = $contribution->user;
            
            $xpToAward = 50;
            if ($user->level <= 15) {
                $xpToAward = 250;
            } elseif ($user->level <= 30) {
                $xpToAward = 100;
            }
            
            $user->addXp($xpToAward);
            
            $achievement = \App\Models\Achievement::where('name', 'Contributor')->first();
            if ($achievement && !$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
            }
            $count++;
        }

        return back()->with('success', "{$count} contributions approved and applied.");
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'contribution_ids' => 'required|array',
            'contribution_ids.*' => 'exists:mob_contributions,id',
        ]);

        MobContribution::whereIn('id', $request->contribution_ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'admin_id' => Auth::id()
            ]);

        return back()->with('success', count($request->contribution_ids) . ' contributions rejected.');
    }
}
