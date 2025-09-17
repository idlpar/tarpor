<?php

namespace App\Http\Controllers;

use App\Models\RewardPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $pointsToApply = $request->points;

        if ($user->rewardPoints->sum('points') < $pointsToApply) {
            return redirect()->back()->withErrors(['points' => 'You do not have enough reward points.']);
        }

        session()->put('rewards', [
            'points' => $pointsToApply,
            'discount' => $pointsToApply * 0.01, // Example: 1 point = $0.01
        ]);

        return redirect()->back()->with('success', 'Reward points applied successfully!');
    }

    public function showMyRewards()
    {
        $user = Auth::user();
        $totalPoints = $user->rewardPoints->sum('points');
        $rewardHistory = $user->rewardPoints()->latest()->get();

        return view('rewards.index', compact('totalPoints', 'rewardHistory'));
    }
}
