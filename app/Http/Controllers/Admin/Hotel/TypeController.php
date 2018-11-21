<?php

namespace App\Http\Controllers\Admin\Hotel;

use App\Model\Admin\Hotel\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('hotel-admin')) {
            $types = Type::all();
            return view('admin.type.show', compact('types'));
        }

        else return redirect(route('admin.home'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (Auth::user()->can('hotel-admin')) {
            return view('admin.type.create');
        }

        else return redirect(route('admin.home'));
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
        if (Auth::user()->can('hotel-admin')) {
            $this->validate($request, [
                'name' => 'required|max:20|unique:types'
            ]);


            $type = Type::create([
                'name' => $request['name'],
                'slug' => str_slug($request['name'], '-')

            ]);

            $type->admin_id = auth()->user()->id;

            $type->save();

            return redirect(route('type.index'));
        }

        else return redirect(route('admin.home'));
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
    public function edit(Type $type)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('hotel-admin')) {
            return view('admin.type.edit', compact('type'));
        }

        else return redirect(route('admin.home'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('hotel-admin')) {
            $types = Type::whereName($request['name'])->get();

            foreach ($types as $existType) {
                if ($existType->name == $request['name'] && $existType->id != $type->id)
                    return redirect()->back()->withErrors(['name' => 'The name has already been taken.']);
            }
            $this->validate($request, [
                'name' => 'required|max:20',
            ]);

            $type->name = $request['name'];
            $type->slug = str_slug($request['name'], '-');
            $type->admin_id = auth()->user()->id;
            $type->save();

            return redirect(route('type.index'));
        }

        else return redirect(route('admin.home'));
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
        if (Auth::user()->can('super-admin')) {
            Type::whereSlug($slug)->delete();

            return redirect()->back();
        }

        else return redirect(route('admin.home'));
    }
}
