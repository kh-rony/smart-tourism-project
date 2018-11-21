<?php

namespace App\Http\Controllers\Admin\Hotel;

use App\Model\Admin\Hotel\Hotel;
use App\Model\Admin\Hotel\Room;
use App\Model\Admin\Hotel\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
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
        if (Auth::user()->can('super-admin')) {

            $unpublishHotels = Hotel::wherePublished(0)->get();
            $hotels = Hotel::wherePublished(1)->get();
            return view('admin.hotel.show', compact('unpublishHotels', 'hotels'));
        }

        else if (Auth::user()->can('hotel-admin')) {

            $hotels = Hotel::whereAdminId(auth()->user()->id)->get();
            return view('admin.hotel.show', compact('hotels'));
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
            $rooms = Room::all();
            $types = Type::all();
            return view('admin.hotel.create', compact('rooms', 'types'));
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
                'name' => 'required|max:50',
                'email' => 'string|email|max:255|unique:hotels',
                'phone' => 'required|numeric|unique:hotels',
                'address1' => 'required|max:100',
                'address2' => 'required|max:100',
                'address3' => 'required|max:100',
                'zip_code' => 'required|max:10',
                'city' => 'required|max:30',
                'state' => 'required|max:30',
                'country' => 'required|max:30',
                'latitude' => 'required',
                'longitude' => 'required',
                'total_rooms' => 'required|numeric',
                'type' => 'required',
                'images' => 'required',
                'rooms' => 'required',
                'top_amenities' => 'required',
                'top_amenities.*' => 'required',
                'hotel_amenities' => 'required',
                'hotel_amenities.*' => 'required',
                'room_amenities' => 'required',
                'room_amenities.*' => 'required',
                'body' => 'required'
            ]);

            if ($request['email']) {
                $hotels = Hotel::whereEmail($request['email']);
                if ($hotels->count()) {
                    return redirect()->back()->withErrors(['email' => 'The email has already been taken.']);
                }
            }

            $type = Type::whereSlug($request['type'])->first();

            $i = 0;
            $totalAmenities = count($request['top_amenities']);

            $topAmenities = '{"amenities":[';
            foreach ($request['top_amenities'] as $amenity) {
                $topAmenities .= '{"name": "' . $amenity . '"}';
                if ($i + 1 < $totalAmenities) {
                    $topAmenities .= ',';
                }
                $i++;
            }
            $topAmenities .= ']}';

            $i = 0;
            $totalAmenities = count($request['hotel_amenities']);

            $hotelAmenities = '{"amenities":[';
            foreach ($request['hotel_amenities'] as $amenity) {
                $hotelAmenities .= '{"name": "' . $amenity . '"}';
                if ($i + 1 < $totalAmenities) {
                    $hotelAmenities .= ',';
                }
                $i++;
            }
            $hotelAmenities .= ']}';

            $i = 0;
            $totalAmenities = count($request['room_amenities']);

            $roomAmenities = '{"amenities":[';
            foreach ($request['room_amenities'] as $amenity) {
                $roomAmenities .= '{"name": "' . $amenity . '"}';
                if ($i + 1 < $totalAmenities) {
                    $roomAmenities .= ',';
                }
                $i++;
            }
            $roomAmenities .= ']}';

            $hotel = Hotel::create([
                'name' => $request['name'],
                'slug' => str_slug($request['name'] . '-' . $request['city'], '-'),
                'phone' => $request['phone'],
                'address1' => $request['address1'],
                'address2' => $request['address2'],
                'address3' => $request['address3'],
                'zip_code' => $request['zip_code'],
                'city' => $request['city'],
                'state' => $request['state'],
                'country' => $request['country'],
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'total_rooms' => $request['total_rooms'],
                'type_id' => $type->id,
                'top_amenities' => $topAmenities,
                'hotel_amenities' => $hotelAmenities,
                'room_amenities' => $roomAmenities
            ]);

            if ($request['email']) {
                $hotel->email = $request['email'];
            }

            if ($request['website']) {
                $hotel->website = $request['website'];
            }

            $hotel->admin_id = auth()->user()->id;

            $roomId = [];
            foreach ($request->rooms as $room) {
                $existRoom = Room::whereSlug($room)->first();
                array_push($roomId, $existRoom->id);
            }

            $hotel->rooms()->sync($roomId);

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos');
                    $hotel->photos()->create(['img_url' => $imgUrl]);
                }
            }

            $hotel->description()->create(['body' => $request['body']]);

            $hotel->save();


            return redirect(route('hotel.index'));
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
    public function edit(Hotel $hotel)
    {
        //
        if ((Auth::user()->can('hotel-admin') && auth()->user()->id == $hotel->admin_id) || Auth::user()->can('super-admin')) {
            $types = Type::all();
            $rooms = Room::all();
            $topAmenities = json_decode($hotel->top_amenities);
            $hotelAmenities = json_decode($hotel->hotel_amenities);
            $roomAmenities = json_decode($hotel->room_amenities);
            return view('admin.hotel.edit', compact('hotel', 'types', 'rooms', 'topAmenities', 'hotelAmenities', 'roomAmenities'));
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
    public function update(Request $request, Hotel $hotel)
    {
        //
        if ((Auth::user()->can('hotel-admin') && auth()->user()->id == $hotel->admin_id) || Auth::user()->can('super-admin')) {
            if ($request['email']) {
                $hotels = Hotel::whereEmail($request['email'])->get();

                foreach ($hotels as $existHotel) {
                    if ($existHotel->email == $request['email'] && $existHotel->id != $hotel->id)
                        return redirect()->back()->withErrors(['email' => 'The email has already been taken.']);
                }
            }

            $hotels = Hotel::wherePhone($request['phone'])->get();

            foreach ($hotels as $existHotel) {
                if ($existHotel->phone == $request['phone'] && $existHotel->id != $hotel->id)
                    return redirect()->back()->withErrors(['phone' => 'The phone has already been taken.']);
            }

            $this->validate($request, [
                'name' => 'required|max:50',
                'phone' => 'required|numeric',
                'address1' => 'required|max:100',
                'address2' => 'required|max:100',
                'address3' => 'required|max:100',
                'zip_code' => 'required|max:10',
                'city' => 'required|max:30',
                'state' => 'required|max:30',
                'country' => 'required|max:30',
                'latitude' => 'required',
                'longitude' => 'required',
                'total_rooms' => 'required|numeric',
                'type' => 'required',
//            'img_url' => 'required',
                'rooms' => 'required',
                'top_amenities' => 'required',
                'top_amenities.*' => 'required',
                'hotel_amenities' => 'required',
                'hotel_amenities.*' => 'required',
                'room_amenities' => 'required',
                'room_amenities.*' => 'required',
                'body' => 'required'
            ]);

            $type = Type::whereSlug($request['type'])->first();

            $i = 0;
            $totalAmenities = count($request['top_amenities']);

            $topAmenities = '{"amenities":[';
            foreach ($request['top_amenities'] as $amenity) {
                $topAmenities .= '{"name": "' . $amenity . '"}';
                if ($i + 1 < $totalAmenities) {
                    $topAmenities .= ',';
                }
                $i++;
            }
            $topAmenities .= ']}';

            $i = 0;
            $totalAmenities = count($request['hotel_amenities']);

            $hotelAmenities = '{"amenities":[';
            foreach ($request['hotel_amenities'] as $amenity) {
                $hotelAmenities .= '{"name": "' . $amenity . '"}';
                if ($i + 1 < $totalAmenities) {
                    $hotelAmenities .= ',';
                }
                $i++;
            }
            $hotelAmenities .= ']}';

            $i = 0;
            $totalAmenities = count($request['room_amenities']);

            $roomAmenities = '{"amenities":[';
            foreach ($request['room_amenities'] as $amenity) {
                $roomAmenities .= '{"name": "' . $amenity . '"}';
                if ($i + 1 < $totalAmenities) {
                    $roomAmenities .= ',';
                }
                $i++;
            }
            $roomAmenities .= ']}';

            $hotel->name = $request['name'];
            $hotel->slug = str_slug($request['name'] . '-' . $request['city'], '-');
            $hotel->phone = $request['phone'];
            $hotel->address1 = $request['address1'];
            $hotel->address2 = $request['address2'];
            $hotel->address3 = $request['address3'];
            $hotel->zip_code = $request['zip_code'];
            $hotel->city = $request['city'];
            $hotel->state = $request['state'];
            $hotel->country = $request['country'];
            $hotel->latitude = $request['latitude'];
            $hotel->longitude = $request['longitude'];
            $hotel->total_rooms = $request['total_rooms'];
            $hotel->type_id = $type->id;
            $hotel->top_amenities = $topAmenities;
            $hotel->hotel_amenities = $hotelAmenities;
            $hotel->room_amenities = $roomAmenities;

            if ($request['email']) {
                $hotel->email = $request['email'];
            }

            if ($request['website']) {
                $hotel->website = $request['website'];
            }

            $roomId = [];
            foreach ($request->rooms as $room) {
                $existRoom = Room::whereSlug($room)->first();
                array_push($roomId, $existRoom->id);
            }

            $hotel->rooms()->sync($roomId);

            /*if ($request->hasFile('img_url')) {
                foreach ($request->img_url as $url) {
                    $img = $url->store('public/photos');
                    $hotel->photos()->create(['img_url' => $img]);
                }
            }*/

            $hotel->description()->update(['body' => $request['body']]);

            if (Auth::user()->can('super-admin')) {
                $hotel->published = 1;
            }
            else {
                $hotel->published = 0;
            }

            $hotel->save();


            return redirect(route('hotel.index'));
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
        Hotel::whereSlug($slug)->delete();

        return redirect()->back();
    }

    public function publish(Hotel $hotel) {

        if (Auth::user()->can('super-admin')) {
            $hotel->published = 1;
            $hotel->published_by = Auth::user()->id;
            $hotel->save();
            return redirect()->back();
        }

        return redirect(route('admin.home'));
    }
}
