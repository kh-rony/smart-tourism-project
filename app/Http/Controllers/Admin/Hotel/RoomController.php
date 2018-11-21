<?php

namespace App\Http\Controllers\Admin\Hotel;

use App\Model\Admin\Hotel\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
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
            $rooms = Room::all();
            return view('admin.room.show', compact('rooms'));
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
            return view('admin.room.create');
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
                'name' => 'required|max:20|unique:rooms'
            ]);


            $room = Room::create([
                'name' => $request['name'],
                'slug' => str_slug($request['name'], '-')

            ]);

            $room->admin_id = auth()->user()->id;

            $room->save();

            return redirect(route('room.index'));
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
    public function edit(Room $room)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('hotel-admin')) {
            return view('admin.room.edit', compact('room'));
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
    public function update(Request $request, Room $room)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('hotel-admin')) {
            $rooms = Room::whereName($request['name'])->get();

            foreach ($rooms as $existRoom) {
                if ($existRoom->name == $request['name'] && $existRoom->id != $room->id)
                    return redirect()->back()->withErrors(['name' => 'The name has already been taken.']);
            }
            $this->validate($request, [
                'name' => 'required|max:20',
            ]);

            $room->name = $request['name'];
            $room->slug = str_slug($request['name'], '-');
            $room->admin_id = auth()->user()->id;
            $room->save();

            return redirect(route('room.index'));
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
            Room::whereSlug($slug)->delete();

            return redirect()->back();
        }

        else return redirect(route('admin.home'));
    }
}
