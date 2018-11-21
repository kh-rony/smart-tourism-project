<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'required|regex:([A-Za-z ]+)|max:255',
            'phone' => 'required|regex:([0-9]+)|max:11'
        ]);

        $admin = Admin::find(auth()->user()->id);

        if ($admin) {
            $admin->name = $request['name'];
            $admin->phone = $request['phone'];

            if ($request['image']) {
                $fileData = $request['image'];
                $fileName = $admin->name . '_' . time() . '.png';
                list($type, $fileData) = explode(';', $fileData);
                list(, $fileData) = explode(',', $fileData);

                if ($fileData != "") {
                    $admin->image = $fileName;
                    Storage::disk("public")->put('/photos/admins/' . $fileName, base64_decode($fileData));
                }
            }

            $admin->save();
            return back();
        }
        return response(['error' => 'Invalid Request'], 400);
    }
}
