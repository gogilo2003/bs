<?php

namespace App\Http\Controllers;

use App\Services\ReadingStatsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected ReadingStatsService $statsService)
    {
    }

    /**
     * Dashboard function
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $weeklyStats = $this->statsService->getStatistics('week', $user);
        $monthlyStats = $this->statsService->getStatistics('month', $user);
        $quarterlyStats = $this->statsService->getStatistics('quarter', $user);
        $allTimeStats = $this->statsService->getStatistics('all', $user);

        $last7DaysReadings = $this->statsService->getLast7DaysReadings($user);

        return inertia('Dashboard', [
            'weeklyStats' => $weeklyStats,
            'monthlyStats' => $monthlyStats,
            'quarterlyStats' => $quarterlyStats,
            'allTimeStats' => $allTimeStats,
            'last7DaysReadings' => $last7DaysReadings,
        ]);
    }
}
