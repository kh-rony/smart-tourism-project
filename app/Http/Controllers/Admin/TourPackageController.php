<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin\Place\Category;
use App\Model\Admin\Place\Tag;
use App\Model\Admin\TourPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TourPackageController extends Controller
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
        if (Auth::user()->can('super-admin')) {

            $unpublishTourPackages = TourPackage::wherePublished(0)->get();
            $tourPackages = TourPackage::wherePublished(1)->get();
            return view('admin.tourPackage.show', compact('unpublishTourPackages', 'tourPackages'));
        } else if (Auth::user()->can('travel-agent')) {

            $tourPackages = TourPackage::whereAdminId(auth()->user()->id)->get();
            return view('admin.tourPackage.show', compact('tourPackages'));
        } else return redirect(route('admin.home'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('travel-agent')) {
            $categories = Category::all();
            $tags = Tag::all();

            return view('admin.tourPackage.create', compact('categories', 'tags'));
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
        if (Auth::user()->can('travel-agent')) {
            $this->validate($request, [
                'name' => 'required|max:255',
                'duration' => 'required|max:50',
                'origin' => 'required|max:50',
                'destination' => 'required|max:50',
                'transport' => 'required|max:50',
                'tax' => 'required|max:3',
                'accommodation' => 'required|max:50',
                'breakfast' => 'required|max:255',
                'lunch' => 'required|max:255',
                'dinner' => 'required|max:255',
                'sight_seeing' => 'required|max:50',
                'guide_service' => 'required|max:3',
                'plans' => 'required',
                'price' => 'required',
                'images' => 'required',
                'categories' => 'required',
                'tags' => 'required',
                'description' => 'required'
            ]);

            $token = $this->generateToken();

            $tourPackage = Auth::user()->tourPackages()->create([
                'name' => $request['name'],
                'duration' => $request['duration'],
                'origin' => $request['origin'],
                'destination' => $request['destination'],
                'transport' => $request['transport'],
                'tax' => $request['tax'],
                'accommodation' => $request['accommodation'],
                'breakfast' => $request['breakfast'],
                'lunch' => $request['lunch'],
                'dinner' => $request['dinner'],
                'sight_seeing' => $request['sight_seeing'],
                'guide_service' => $request['guide_service'],
                'plans' => json_encode($request['plans']),
                'price' => $request['price'],
                'token' => $token
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos/tourPackage/' . $tourPackage->id);
                    $tourPackage->photos()->create(['img_url' => $imgUrl]);
                }
            }

            $categoryId = [];
            foreach ($request->categories as $category) {
                $existCategory = Category::whereSlug($category)->first();
                array_push($categoryId, $existCategory->id);
            }

            $tourPackage->categories()->sync($categoryId);

            $tagId = [];
            foreach ($request->tags as $tag) {
                $existTag = Tag::whereSlug($tag)->first();
                array_push($tagId, $existTag->id);
            }

            $tourPackage->tags()->sync($tagId);

            $tourPackage->description()->create(['body' => $request['description']]);


            return redirect(route('tour-package.index'));
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
     * @param TourPackage $tourPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(TourPackage $tourPackage)
    {
        if ((Auth::user()->can('travel-agent') && auth()->user()->id == $tourPackage->admin_id) || Auth::user()->can('super-admin')) {
            $categories = Category::all();
            $tags = Tag::all();
            $tourPackage->plans = json_decode($tourPackage->plans);
//            dd($tourPackage);
            return view('admin.tourPackage.edit', compact('tourPackage', 'categories', 'tags'));
        } else return redirect(route('admin.home'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param TourPackage $tourPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TourPackage $tourPackage)
    {
        if ((Auth::user()->can('travel-agent') && auth()->user()->id == $tourPackage->admin_id) || Auth::user()->can('super-admin')) {
            $this->validate($request, [
                'name' => 'required|max:255',
                'duration' => 'required|max:50',
                'origin' => 'required|max:50',
                'destination' => 'required|max:50',
                'transport' => 'required|max:50',
                'tax' => 'required|max:3',
                'accommodation' => 'required|max:50',
                'breakfast' => 'required|max:100',
                'lunch' => 'required|max:100',
                'dinner' => 'required|max:100',
                'sight_seeing' => 'required|max:50',
                'guide_service' => 'required|max:3',
                'plans' => 'required',
                'price' => 'required',
//                'images' => 'required',
                'categories' => 'required',
                'tags' => 'required',
                'description' => 'required'
            ]);

            $tourPackage->name = $request['name'];
            $tourPackage->duration = $request['duration'];
            $tourPackage->origin = $request['origin'];
            $tourPackage->description()->update(['body' => $request['destination']]);
            $tourPackage->transport = $request['transport'];
            $tourPackage->tax = $request['tax'];
            $tourPackage->accommodation = $request['accommodation'];
            $tourPackage->breakfast = $request['breakfast'];
            $tourPackage->lunch = $request['lunch'];
            $tourPackage->dinner = $request['dinner'];
            $tourPackage->sight_seeing = $request['sight_seeing'];
            $tourPackage->guide_service = $request['guide_service'];
            $tourPackage->plans = json_encode($request['plans']);
            $tourPackage->price = $request['price'];

            $categoryId = [];
            foreach ($request->categories as $category) {
                $existCategory = Category::whereSlug($category)->first();
                array_push($categoryId, $existCategory->id);
            }

            $tourPackage->categories()->sync($categoryId);

            $tagId = [];
            foreach ($request->tags as $tag) {
                $existTag = Tag::whereSlug($tag)->first();
                array_push($tagId, $existTag->id);
            }

            $tourPackage->tags()->sync($tagId);

            $tourPackage->description()->update(['body' => $request['description']]);

            if (Auth::user()->can('super-admin')) {
                $tourPackage->published = 1;
            } else {
                $tourPackage->published = 0;
            }

            $tourPackage->save();


            return redirect(route('tour-package.index'));
        } else return redirect(route('admin.home'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $tourPackage = TourPackage::whereSlug($slug)->first();

        if ((Auth::user()->can('travel-agent') && auth()->user()->id === $tourPackage->admin_id) || Auth::user()->can('super-admin')) {
            $tourPackage->delete();

            return redirect()->back();
        } else return redirect(route('admin.home'));
    }

    public function publish(TourPackage $tourPackage)
    {
        if (Auth::user()->can('super-admin')) {
            $tourPackage->published = 1;
            $tourPackage->published_by = Auth::user()->id;
            $tourPackage->save();
            return redirect()->back();
        }

        return redirect(route('admin.home'));
    }

    protected function generateToken()
    {
        $generating = true;
        while($generating) {
            $token = Str::random(8);
            $tourPackages = TourPackage::whereToken($token)->get();
            if (!$tourPackages->count())
                $generating = false;
        }
        return $token;
    }
}
