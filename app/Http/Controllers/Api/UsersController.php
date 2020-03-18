<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'required|string|min:6|max:191',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            //'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);
    }

    public function profile()
    {
        return auth()->user();
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6|max:191',
        ]);

        if ($request->photo != $user->photo) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/'.$name));

            // delete old photo
            $old_photo = public_path('img/profile/') . $user->photo;
            if (file_exists($old_photo))
                @unlink($old_photo);

            $request->merge(['photo' => $name]);
        }

        if (! empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
    }

    public function show($id)
    {
        //
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
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6|max:191',
        ]);

        $user->update($request->all());

        return ['message' => 'user updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message' => 'User Deleted'];
    }
}
