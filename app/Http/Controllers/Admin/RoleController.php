<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
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
        $roles = Role::all();
        return view('admin.role.show', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.role.create');
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
            'name' => 'required|max:20|unique:roles'
        ]);

        Role::create([
            'name' => $request['name'],
            'slug' => str_slug($request['name'], '-')
        ]);


        return redirect(route('role.index'));
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
    public function edit(Role $role)
    {
        //

        return view('admin.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
        $roles = Role::whereName($request['name'])->get();

        foreach ($roles as $existRole) {
            if ($existRole->name == $request['name'] && $existRole->id != $role->id)
                return redirect()->back()->withErrors(['name' => 'The name has already been taken.']);
        }
        $this->validate($request, [
            'name' => 'required|max:20',
        ]);


        $role->name = $request['name'];
        $role->slug = str_slug($request['name'], '-');

        $role->save();

        return redirect(route('role.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        //
        Role::whereSlug($slug)->delete();

        return redirect()->back();
    }
}
