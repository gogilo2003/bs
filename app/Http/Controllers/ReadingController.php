<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use Inertia\Inertia;
use App\Models\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\StoreReadingRequest;
use App\Http\Requests\UpdateReadingRequest;

class ReadingController extends Controller
{
    protected  $type = [
        'rbs' => 'Random Blood Sugar',
        'fbs' => 'Fasting Blood Sugar',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $readings = Reading::orderBy('created_at', 'DESC')
            ->paginate(8)
            ->through(fn (Reading $reading) => [
                'id' => $reading->id,
                'read_at' => Carbon::parse($reading->read_at)->isoFormat('ddd, D MMM Y h:mm:ss A'),
                // 'read_at' => $reading->read_at->isoFormat('ddd, D MMM Y h:mm:ss A'),
                'reading' => $reading->reading,
                'type' => [
                    "value" => $reading->type,
                    "name" => $this->type[$reading->type]
                ],
            ]);
        return Inertia::render('Readings', ['readings' => $readings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function reports()
    {
        $readings = Reading::orderBy('read_at', 'DESC')
            ->get()
            ->map(fn (Reading $reading) => [
                'id' => $reading->id,
                'read_at' => Carbon::parse($reading->read_at)->isoFormat('ddd, D MMM Y h:mm:ss A'),
                'reading' => $reading->reading,
                'type' => [
                    "value" => $reading->type,
                    "name" => $this->type[$reading->type]
                ],
            ]);

        return Inertia::render('Report', ['readings' => $readings]);
    }

    /**
     * Download pdf report
     *
     */
    public function download($type = "all")
    {
        $readings = Reading::orderBy('read_at', 'DESC');

        if ($type == 'today') {
            $date = new DateTime();
            $date->setTime(0, 0, 0, 0);
            $readings->where('read_at', ">=", $date);
        }

        if ($type == 'week') {
            $date = new DateTime();
            $date->sub(DateInterval::createFromDateString("1 week"));
            $readings->where('read_at', '>=', $date);
        }

        if ($type == 'month') {
            $date = new DateTime();
            $date->sub(DateInterval::createFromDateString("1 month"));
            $readings->where('read_at', '>=', $date);
        }

        if ($type == 'quarterly') {
            $date = new DateTime();
            $date->sub(DateInterval::createFromDateString("3 month"));
            $readings->where('read_at', '>=', $date);
        }

        $readings = $readings->get();

        $pdf = \Illuminate\Support\Facades\App::make('snappy.pdf.wrapper');
        // $pdf->setPaper('b10');
        $height = 7 * ($readings->count() ? $readings->count() : 1);
        $height *= ($height <= 110 ? 0.78 : 0.8);
        $height = (30 + ($height < 7 ? 7 : $height)) . "mm";
        return $pdf->setOption('page-width', '85mm')
            ->setOption('page-height', $height)
            ->setOption('margin-left', "0mm")
            ->setOption('margin-right', "0mm")
            ->setOption('margin-bottom', "0mm")
            ->setOption('margin-top', "0mm")
            ->loadView('pdf.readings', compact('readings', 'height'))
            ->download('readings.pdf');
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
     * Display the specified resource.
     */
    public function show(Reading $reading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reading $reading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReadingRequest $request, Reading $reading)
    {
        $reading->reading = $request->reading;
        $reading->type = $request->type;
        $reading->read_at = Carbon::parse($request->read_at);

        $user = $request->user();

        $user->readings()->save($reading);

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
