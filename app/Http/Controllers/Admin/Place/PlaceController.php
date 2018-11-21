<?php

namespace App\Http\Controllers\Admin\Place;

use App\Model\Admin\Place\Category;
use App\Model\Admin\Place\Place;
use App\Model\Admin\Place\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::user()->can('super-admin')) {

            $unpublishPlaces = Place::wherePublished(0)->get();
            $places = Place::wherePublished(1)->get();
            return view('admin.place.show', compact('unpublishPlaces', 'places'));
        } else if (Auth::user()->can('place-admin')) {

            $places = Place::whereAdminId(auth()->user()->id)->get();
            return view('admin.place.show', compact('places'));
        } else return redirect(route('admin.home'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (Auth::user()->can('place-admin')) {
            $categories = Category::all();
            $tags = Tag::all();
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            return view('admin.place.create', compact('days', 'categories', 'tags'));
        } else return redirect(route('admin.home'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (Auth::user()->can('place-admin')) {
            $this->validate($request, [
                'name' => 'required|max:50',
                'address1' => 'required|max:100',
                'address2' => 'required|max:100',
                'address3' => 'required|max:100',
                'zip_code' => 'required|max:10',
                'city' => 'required|max:30',
                'state' => 'required|max:30',
                'country' => 'required|max:30',
                'latitude' => 'required',
                'longitude' => 'required',
                'images' => 'required',
                'categories' => 'required',
                'tags' => 'required',
                'body' => 'required'
            ]);

            $place = Place::create([
                'name' => $request['name'],
                'slug' => str_slug($request['name'] . '-' . $request['city'], '-'),
                'address1' => $request['address1'],
                'address2' => $request['address2'],
                'address3' => $request['address3'],
                'zip_code' => $request['zip_code'],
                'city' => $request['city'],
                'state' => $request['state'],
                'country' => $request['country'],
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude']
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos');
                    $place->photos()->create(['img_url' => $imgUrl]);
                }
            }

            $place->admin_id = auth()->user()->id;

            if ($request['website']) {
                $place->website = $request['website'];
            }

            if ($request['weekly_holiday']) {
                $place->weekly_holiday = $request['weekly_holiday'];
            }

            if ($request['start_hour']) {
                $place->start_hour = $request['start_hour'];
            }

            if ($request['end_hour']) {
                $place->end_hour = $request['end_hour'];
            }

            if ($request['price']) {
                $place->price = $request['price'];
            }

            $categoryId = [];
            foreach ($request->categories as $category) {
                $existCategory = Category::whereSlug($category)->first();
                array_push($categoryId, $existCategory->id);
            }

            $place->categories()->sync($categoryId);

            $tagId = [];
            foreach ($request->tags as $tag) {
                $existTag = Tag::whereSlug($tag)->first();
                array_push($tagId, $existTag->id);
            }

            $place->tags()->sync($tagId);

            $place->description()->create(['body' => $request['body']]);

            $place->save();


            return redirect(route('place.index'));
        } else return redirect(route('admin.home'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        //
        if ((Auth::user()->can('place-admin') && auth()->user()->id == $place->admin_id) || Auth::user()->can('super-admin')) {
            $categories = Category::all();
            $tags = Tag::all();
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            return view('admin.place.edit', compact('place', 'days', 'categories', 'tags'));
        } else return redirect(route('admin.home'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        //
        if ((Auth::user()->can('place-admin') && auth()->user()->id == $place->admin_id) || Auth::user()->can('super-admin')) {
            $this->validate($request, [
                'name' => 'required|max:50',
                'address1' => 'required|max:100',
                'address2' => 'required|max:100',
                'address3' => 'required|max:100',
                'zip_code' => 'required|max:10',
                'city' => 'required|max:30',
                'state' => 'required|max:30',
                'country' => 'required|max:30',
                'latitude' => 'required',
                'longitude' => 'required',
//            'img_url' => 'required',
                'categories' => 'required',
                'tags' => 'required',
                'body' => 'required'
            ]);

            $place->name = $request['name'];
            $place->slug = str_slug($request['name'] . '-' . $request['city'], '-');
            $place->address1 = $request['address1'];
            $place->address2 = $request['address2'];
            $place->address3 = $request['address3'];
            $place->zip_code = $request['zip_code'];
            $place->city = $request['city'];
            $place->state = $request['state'];
            $place->country = $request['country'];
            $place->latitude = $request['latitude'];
            $place->longitude = $request['longitude'];

            /*        if ($request->hasFile('img_url')) {
                        foreach ($request->img_url as $url) {
                            $img = $url->store('public/photos');
                            $place->photos()->update(['img_url' => $img]);
                        }
                    }*/

            if ($request['website']) {
                $place->website = $request['website'];
            }

            if ($request['weekly_holiday']) {
                $place->weekly_holiday = $request['weekly_holiday'];
            }

            if ($request['start_hour']) {
                $place->start_hour = $request['start_hour'];
            }

            if ($request['end_hour']) {
                $place->end_hour = $request['end_hour'];
            }

            if ($request['price']) {
                $place->price = $request['price'];
            }

            $categoryId = [];
            foreach ($request->categories as $category) {
                $existCategory = Category::whereSlug($category)->first();
                array_push($categoryId, $existCategory->id);
            }

            $place->categories()->sync($categoryId);

            $tagId = [];
            foreach ($request->tags as $tag) {
                $existTag = Tag::whereSlug($tag)->first();
                array_push($tagId, $existTag->id);
            }

            $place->tags()->sync($tagId);

            $place->description()->update(['body' => $request['body']]);

            if (Auth::user()->can('super-admin')) {
                $place->published = 1;
            } else {
                $place->published = 0;
            }

            $place->save();


            return redirect(route('place.index'));
        } else return redirect(route('admin.home'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        //
        $place = Place::whereSlug($slug)->first();

        if ((Auth::user()->can('place-admin') && auth()->user()->id === $place->admin_id) || Auth::user()->can('super-admin')) {
            $place->delete();

            return redirect()->back();
        } else return redirect(route('admin.home'));
    }

    public function publish(Place $place)
    {

        if (Auth::user()->can('super-admin')) {
            $place->published = 1;
            $place->published_by = Auth::user()->id;
            $place->save();
            return redirect()->back();
        }

        return redirect(route('admin.home'));
    }
}
