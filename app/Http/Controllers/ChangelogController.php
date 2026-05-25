<?php

namespace App\Http\Controllers;

use App\Models\SystemChangelog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangelogController extends Controller
{
    public function index()
    {
        $changelogs = SystemChangelog::latest()->get();
        return view('changelogs.index', compact('changelogs'));
    }

    public function generate(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403, 'Unauthorized.');

        $lastChangelog = SystemChangelog::latest()->first();
        
        // If there's a previous changelog, get commits since its creation date.
        // Otherwise, get last 50 commits (or all).
        if ($lastChangelog) {
            $since = $lastChangelog->created_at->toRfc2822String();
            $gitCommand = "git log --since=\"{$since}\" --pretty=format:\"%h - %s\"";
        } else {
            $gitCommand = "git log -n 50 --pretty=format:\"%h - %s\"";
        }

        $rawCommits = shell_exec($gitCommand);

        if (empty(trim($rawCommits))) {
            return back()->with('error', 'No new commits found since last changelog.');
        }

        $oracle = app(\App\Http\Controllers\OracleController::class);
        
        try {
            $aiData = $oracle->generateChangelog($rawCommits, $lastChangelog?->version);

            SystemChangelog::create([
                'version' => $aiData['version'] ?? 'v0.0.1',
                'title' => $aiData['title'] ?? 'System Update',
                'ai_summary' => $aiData['markdown_content'] ?? '',
                'raw_commits' => $rawCommits,
            ]);

            return back()->with('success', 'A new changelog has been generated automatically!');
        } catch (\Exception $e) {
            return back()->with('error', 'Oracle Error: ' . $e->getMessage());
        }
    }
}
