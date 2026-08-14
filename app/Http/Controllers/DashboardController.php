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
        // Step 1: Get the last N days with at least one reading
        $dates = Reading::select(DB::raw('DATE(read_at) as date'))
            ->distinct()
            ->orderBy('date', 'desc')
            ->take(7)
            ->pluck('date')
            ->sort()
            ->values();

        // Step 2: Fetch mean readings for these dates
        $readings = Reading::select(
            DB::raw('DATE(read_at) as date'),
            'type',
            DB::raw('AVG(reading) as mean_reading')
        )
            ->whereIn(DB::raw('DATE(read_at)'), $dates)
            ->groupBy('date', 'type')
            ->get();

        // Step 3: Fetch all historical readings for interpolation reference
        $allHistory = Reading::select(
            DB::raw('DATE(read_at) as date'),
            'type',
            DB::raw('AVG(reading) as mean_reading')
        )
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get()
            ->groupBy('type');

        $types = ['fbs', 'rbs'];
        $normalized = collect();

        foreach ($dates as $date) {
            foreach ($types as $type) {
                $existing = $readings->firstWhere(fn($r) => $r->date === $date && $r->type === $type);

                if ($existing) {
                    $normalized->push([
                        'date'         => $date,
                        'type'         => $type,
                        'mean_reading' => round($existing->mean_reading, 1),
                    ]);
                } else {
                    // Find closest before & after for THIS type
                    $recordsForType = $allHistory->get($type, collect());
                    $prev = $recordsForType->where('date', '<', $date)->last();
                    $next = $recordsForType->where('date', '>', $date)->first();

                    if ($prev && $next) {
                        $avg = ($prev->mean_reading + $next->mean_reading) / 2;
                    } elseif ($prev) {
                        $avg = $prev->mean_reading;
                    } elseif ($next) {
                        $avg = $next->mean_reading;
                    } else {
                        $avg = null;
                    }

                    $normalized->push([
                        'date'         => $date,
                        'type'         => $type,
                        'mean_reading' => $avg !== null ? round($avg, 1) : null,
                    ]);
                }
            }
        }

        // Step 4: Group by date for final output
        return $normalized
            ->sortByDesc('date')
            ->groupBy('date')
            ->toArray();
    }
}
