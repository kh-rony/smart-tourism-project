<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin\Admin;
use App\Model\Admin\Role;
use App\Notifications\AdminRegistration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('can:super-admin');
    }

    public function index()
    {
        //
        $admins = Admin::orderBy('name', 'asc')->get();
        return view('admin.user.show', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'email' => 'required|string|email|max:255|unique:admins',
            'role' => 'required'
        ]);

        $role = Role::whereSlug($request['role'])->first();

        $admin = new Admin;
        $admin->email = $request['email'];
        $admin->email_token = Hash::make($request['email'] . Str::random(60));
        $admin->role_id = $role->id;
        $admin->save();

        $admin->notify(new AdminRegistration($admin, $role->name, route('admin.register.request', base64_encode($admin->email_token))));


        return redirect(route('admin.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
        $roles = Role::all();
        return view('admin.user.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $this->validate($request, [
            'role' => 'required'
        ]);

        $request->status? : $request['status'] = 0;
        $role = Role::whereSlug($request['role'])->first();

        $admin->status = $request['status'];
        $admin->role_id = $role->id;
        $admin->save();

        return redirect(route('admin.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Admin::whereId($id)->delete();

        return redirect()->back();
    }
}
