<?php

namespace App\Services;

use App\Models\Reading;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReadingStatsService
{
    public function getStatistics(string $period, $user = null): array
    {
        $now = Carbon::now();
        $start = match ($period) {
            'week' => $now->copy()->subWeek(),
            'month' => $now->copy()->subMonth(),
            'quarter' => $now->copy()->subMonths(3),
            'all' => Carbon::createFromTimestamp(0),
            default => $now,
        };

        $query = Reading::where('read_at', '>=', $start);
        if ($user) {
            $query->where('user_id', is_object($user) ? $user->id : $user);
        }
        $readings = $query->get();

        $count = $readings->count();

        return [
            'min' => $count ? round((float) $readings->min('reading'), 1) : 0,
            'max' => $count ? round((float) $readings->max('reading'), 1) : 0,
            'mean' => $count ? round((float) $readings->avg('reading'), 1) : 0,
        ];
    }

    public function getLast7DaysReadings($user = null): array
    {
        $baseQuery = Reading::query();
        if ($user) {
            $baseQuery->where('user_id', is_object($user) ? $user->id : $user);
        }

        // Step 1: Get the last 7 distinct dates with at least one reading
        $dates = (clone $baseQuery)->select(DB::raw('DATE(read_at) as date'))
            ->distinct()
            ->orderBy('date', 'desc')
            ->take(7)
            ->pluck('date')
            ->sort()
            ->values();

        if ($dates->isEmpty()) {
            return [];
        }

        // Step 2: Fetch mean readings for these dates
        $readings = (clone $baseQuery)->select(
            DB::raw('DATE(read_at) as date'),
            'type',
            DB::raw('AVG(reading) as mean_reading')
        )
            ->whereIn(DB::raw('DATE(read_at)'), $dates)
            ->groupBy('date', 'type')
            ->get();

        // Step 3: Fetch all historical readings for interpolation reference
        $allHistory = (clone $baseQuery)->select(
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
                        'mean_reading' => round((float) $existing->mean_reading, 1),
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
                        'mean_reading' => $avg !== null ? round((float) $avg, 1) : null,
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
