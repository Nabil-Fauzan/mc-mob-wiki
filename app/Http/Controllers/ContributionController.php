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
        $contributions = MobContribution::with(['mob', 'user'])->pending()->latest()->get();
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

    public function approve(Request $request, MobContribution $contribution, \App\Services\ContributionService $service)
    {
        $service->approve($contribution);
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

    public function bulkApprove(Request $request, \App\Services\ContributionService $service)
    {
        $request->validate([
            'contribution_ids' => 'required|array',
            'contribution_ids.*' => 'exists:mob_contributions,id',
        ]);

        $contributions = MobContribution::whereIn('id', $request->contribution_ids)
            ->pending()
            ->get();

        // Fetch achievement once outside the loop to prevent N+1 queries
        $achievement = \App\Models\Achievement::where('name', 'Contributor')->first();

        $count = 0;
        foreach ($contributions as $contribution) {
            $service->approve($contribution, $achievement);
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
