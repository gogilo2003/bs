<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dasboard function
     *
     * @return void
     */
    public function index()
    {
        $weeklyStats = $this->getStatistics('week');
        $monthlyStats = $this->getStatistics('month');
        $quarterlyStats = $this->getStatistics('quarter');
        $allTimeStats = $this->getStatistics('all');

        $last7DaysReadings = $this->getLast7DaysReadings();

        return inertia('Dashboard', [
            'weeklyStats' => $weeklyStats,
            'monthlyStats' => $monthlyStats,
            'quarterlyStats' => $quarterlyStats,
            'allTimeStats' => $allTimeStats,
            'last7DaysReadings' => $last7DaysReadings,
        ]);
    }

    private function getStatistics($period)
    {
        $now = Carbon::now();
        $start = match ($period) {
            'week' => $now->subWeek(),
            'month' => $now->subMonth(),
            'quarter' => $now->subMonths(3),
            'all' => Carbon::createFromTimestamp(0),
            default => $now,
        };

        $readings = Reading::where('read_at', '>=', $start)->get();

        return [
            'min' => round($readings->min('reading'), 1),
            'max' => round($readings->max('reading'), 1),
            'mean' => round($readings->avg('reading'), 1),
        ];
    }

    private function getLast7DaysReadings()
    {
        $readings = Reading::select(
            DB::raw('DATE(read_at) as date'),
            'type',
            DB::raw('AVG(reading) as mean_reading')
        )
            ->groupBy('date', 'type')
            ->orderBy('date', 'desc')
            ->take(14) // Take more to ensure we get at least 7 days with readings
            ->get()
            ->groupBy('date')
            ->take(7); // Now limit to 7 unique dates

        return $readings;
    }
}
