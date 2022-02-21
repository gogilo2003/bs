<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Reading;
use Illuminate\Http\Request;
use Ogilo\ApiResponseHelpers;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReadingResource;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Translation\Loader\CsvFileLoader;

class ReadingController extends Controller
{
    use ApiResponseHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReadingResource::collection(Reading::orderBy('read_at', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'reading' => 'required|numeric',
            'type' => ['required', Rule::in(['fbs', 'rbs'])],
            'read_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $reading = new Reading;
        $reading->reading = $request->reading;
        $reading->type = $request->type;
        $reading->read_at = new DateTime($request->read_at);

        $user = $request->user();

        $user->readings()->save($reading);

        return $this->storeSuccess("Reading stored", ['reading' => new ReadingResource($reading)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(compact('id'), [
            'id' => 'required|integer|exists:readings,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        return new ReadingResource(Reading::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:readings,id',
            'reading' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $reading = Reading::find($id);
        $reading->reading = $request->reading;
        $reading->type = $request->type;
        $reading->read_at = new DateTime($request->read_at);
        $reading->save();

        return $this->updateSuccess("Reading updated", ['reading' => new ReadingResource($reading)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(compact('id'), [
            'id' => 'required|integer|exists:readings,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        Reading::destroy($id);
        return $this->deleteSuccess();
    }

    /**
     * Upload a and process CSV file containing readings data in the format read_at, type, reading
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // die((new DateTime('Thu 5-Aug-2021 10:30'))->format('Y-m-d H:i:s'));
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $file = $request->file;
        collect(array_map('str_getcsv', file($file->getRealPath())))->each(function ($item) {
            try {
                $reading = new Reading();
                $read_at = new DateTime($item[0]);

                if ($read_at->format('H:i:s') === '00:00:00') {
                    $read_at->setTime(6, 30);
                }
                if (!empty(trim($item[2]))) {
                    $reading->read_at = $read_at->format('Y-m-d H:i:s');
                    $reading->type = empty(trim($item[1])) ? 'fbs' : $item[1];
                    $reading->reading = $item[2];
                    auth()->user()->readings()->save($reading);
                }
            } catch (\Throwable $th) {
            }
        });

        return $this->respondWithSuccess('File uploaded');
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

        $readings = $readings->get();

        $pdf = \Illuminate\Support\Facades\App::make('snappy.pdf.wrapper');
        // $pdf->setPaper('b10');
        $height = 7 * ($readings->count() ? $readings->count() : 1);
        $height *= 0.78;
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
}
