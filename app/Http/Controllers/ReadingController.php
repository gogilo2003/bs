<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReadingRequest;
use App\Http\Requests\UpdateReadingRequest;
use App\Models\Reading;
use App\Services\ReadingStatsService;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ReadingController extends Controller
{
    protected array $type = [
        'rbs' => 'Random Blood Sugar',
        'fbs' => 'Fasting Blood Sugar',
    ];

    public function __construct(protected ReadingStatsService $statsService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $readings = Reading::when($user, fn($q) => $q->where('user_id', $user->id))
            ->orderBy('created_at', 'DESC')
            ->paginate(8)
            ->through(fn(Reading $reading) => [
                'id' => $reading->id,
                'read_at' => Carbon::parse($reading->read_at)->isoFormat('ddd, D MMM Y h:mm:ss A'),
                'reading' => $reading->reading,
                'type' => [
                    "value" => $reading->type,
                    "name" => $this->type[$reading->type] ?? strtoupper($reading->type)
                ],
            ]);

        return Inertia::render('Readings', ['readings' => $readings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function reports(Request $request)
    {
        $user = $request->user();

        $readings = Reading::when($user, fn($q) => $q->where('user_id', $user->id))
            ->orderBy('read_at', 'DESC')
            ->get()
            ->map(fn(Reading $reading) => [
                'id' => $reading->id,
                'read_at' => Carbon::parse($reading->read_at)->isoFormat('ddd, D MMM Y h:mm:ss A'),
                'raw_read_at' => Carbon::parse($reading->read_at)->toIso8601String(),
                'reading' => $reading->reading,
                'type' => [
                    "value" => $reading->type,
                    "name" => $this->type[$reading->type] ?? strtoupper($reading->type)
                ],
            ]);

        $weeklyStats = $this->statsService->getStatistics('week', $user);
        $monthlyStats = $this->statsService->getStatistics('month', $user);
        $quarterlyStats = $this->statsService->getStatistics('quarter', $user);
        $allTimeStats = $this->statsService->getStatistics('all', $user);

        $last7DaysReadings = $this->statsService->getLast7DaysReadings($user);

        return Inertia::render('Report', [
            'readings' => $readings,
            'weeklyStats' => $weeklyStats,
            'monthlyStats' => $monthlyStats,
            'quarterlyStats' => $quarterlyStats,
            'allTimeStats' => $allTimeStats,
            'last7DaysReadings' => $last7DaysReadings,
        ]);
    }

    /**
     * Download pdf report
     */
    public function download(Request $request, $type = "all")
    {
        $user = $request->user();
        $query = Reading::when($user, fn($q) => $q->where('user_id', $user->id))->orderBy('read_at', 'DESC');

        if ($type == 'today') {
            $date = new DateTime();
            $date->setTime(0, 0, 0, 0);
            $query->where('read_at', ">=", $date);
        }

        if ($type == 'week') {
            $date = new DateTime();
            $date->sub(DateInterval::createFromDateString("1 week"));
            $query->where('read_at', '>=', $date);
        }

        if ($type == 'month') {
            $date = new DateTime();
            $date->sub(DateInterval::createFromDateString("1 month"));
            $query->where('read_at', '>=', $date);
        }

        if ($type == 'quarterly') {
            $date = new DateTime();
            $date->sub(DateInterval::createFromDateString("3 month"));
            $query->where('read_at', '>=', $date);
        }

        $readings = $query->get();

        $weeklyStats = $this->statsService->getStatistics('week', $user);
        $monthlyStats = $this->statsService->getStatistics('month', $user);
        $quarterlyStats = $this->statsService->getStatistics('quarter', $user);
        $allTimeStats = $this->statsService->getStatistics('all', $user);
        $last7DaysReadings = $this->statsService->getLast7DaysReadings($user);

        $pdf = \Illuminate\Support\Facades\App::make('snappy.pdf.wrapper');
        return $pdf->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-left', '10mm')
            ->setOption('margin-right', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-top', '10mm')
            ->setOption('enable-local-file-access', true)
            ->loadView('pdf.readings', compact('readings', 'weeklyStats', 'monthlyStats', 'quarterlyStats', 'allTimeStats', 'last7DaysReadings', 'type'))
            ->download('blood_sugar_report.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReadingRequest $request)
    {
        $reading = new Reading;
        $reading->reading = $request->reading;
        $reading->type = $request->type;
        $reading->read_at = Carbon::parse($request->read_at);

        $user = $request->user();
        $user->readings()->save($reading);

        return redirect()->back()->with("success", "Reading stored");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReadingRequest $request, Reading $reading)
    {
        $reading->reading = $request->reading;
        $reading->type = $request->type;
        $reading->read_at = Carbon::parse($request->read_at);

        $reading->save();

        return redirect()->back()->with("success", "Reading updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reading $reading)
    {
        $reading->delete();
        return redirect()->back()->with("success", "Reading deleted");
    }
}
