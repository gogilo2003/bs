<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Ogilo\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponseHelpers;
    /**
     * Display a listing of the resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $validator = Validator::make(['id'=>$id],['id'=>'nullable|exists:users']);
        if($validator->fails()){
            return $this->validationError($validator);
        }

        if($id){
            return new UserResource(User::find($id));
        }
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed',
        ]);
        if($validator->fails()){
            return $this->validationError($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if($user->save()){
            return $this->storeSuccess('User stored',['user'=>new UserResource($user)]);
        }
        return $this->respondWithError('An error occured');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request,[
            'id'=>'required|integer|exists:users',
            'name'=>'required',
            'email'=>'required|email|unique:users',
        ]);

        if($validator->fails()){
            return $this->validationError($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;

        if($user->save()){
            return $this->storeSuccess('User update',['user'=>new UserResource($user)]);
        }
        return $this->respondWithError('An error occured');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id'=>$id],['id'=>'nullable|exists:users']);
        if($validator->fails()){
            return $this->validationError($validator);
        }

        if($id){
            return new UserResource(User::find($id));
        }

        User::destroy($id);
        return $this->deleteSuccess();
    }
}
