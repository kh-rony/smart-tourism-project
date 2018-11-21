<?php

namespace App\Http\Controllers\Admin\Place;

use App\Model\Admin\Place\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
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
        if (Auth::user()->can('super-admin') || Auth::user()->can('place-admin') || Auth::user()->can('article-admin') || Auth::user()->can('travel-agent')) {
            $tags = Tag::all();
            return view('admin.tag.show', compact('tags'));
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
        if (Auth::user()->can('place-admin') || Auth::user()->can('article-admin') || Auth::user()->can('travel-agent')) {
            return view('admin.tag.create');
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
        if (Auth::user()->can('place-admin') || Auth::user()->can('article-admin') || Auth::user()->can('travel-agent')) {
            $this->validate($request, [
                'name' => 'required|max:20|unique:tags'
            ]);


            $tag = Tag::create([
                'name' => $request['name'],
                'slug' => str_slug($request['name'], '-')

            ]);

            $tag->admin_id = auth()->user()->id;

            $tag->save();

            return redirect(route('tag.index'));
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
    public function edit(Tag $tag)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('place-admin') || Auth::user()->can('article-admin') || Auth::user()->can('travel-agent')) {
            return view('admin.tag.edit', compact('tag'));
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
    public function update(Request $request, Tag $tag)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('place-admin') || Auth::user()->can('article-admin') || Auth::user()->can('travel-agent')) {
            $tags = Tag::whereName($request['name'])->get();

            foreach ($tags as $existTag) {
                if ($existTag->name == $request['name'] && $existTag->id != $tag->id)
                    return redirect()->back()->withErrors(['name' => 'The name has already been taken.']);
            }
            $this->validate($request, [
                'name' => 'required|max:20',
            ]);

            $tag->name = $request['name'];
            $tag->slug = str_slug($request['name'], '-');
            $tag->admin_id = auth()->user()->id;
            $tag->save();

            return redirect(route('tag.index'));
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
            Tag::whereSlug($slug)->delete();

            return redirect()->back();
        }

        else return redirect(route('admin.home'));
    }
}
