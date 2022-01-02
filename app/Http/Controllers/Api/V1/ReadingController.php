<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Reading;
use Illuminate\Http\Request;
use Ogilo\ApiResponseHelpers;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReadingResource;
use Illuminate\Support\Facades\Validator;

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
        return ReadingResource::collection(Reading::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $reading->read_at = $request->read_at;

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
        $reading->read_at = $request->read_at;
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
}
