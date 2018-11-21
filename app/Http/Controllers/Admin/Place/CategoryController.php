<?php

namespace App\Http\Controllers\Admin\Place;

use App\Model\Admin\Place\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
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
            $categories = Category::all();
            return view('admin.category.show', compact('categories'));
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
            return view('admin.category.create');
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
                'name' => 'required|max:20|unique:categories'
            ]);


            $category = Category::create([
                'name' => $request['name'],
                'slug' => str_slug($request['name'], '-')
            ]);

            $category->admin_id = auth()->user()->id;

            $category->save();

            return redirect(route('category.index'));
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
    public function edit(Category $category)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('place-admin') || Auth::user()->can('article-admin') || Auth::user()->can('travel-agent')) {
            return view('admin.category.edit', compact('category'));
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
    public function update(Request $request, Category $category)
    {
        //
        if (Auth::user()->can('super-admin') || Auth::user()->can('place-admin') || Auth::user()->can('article-admin') || Auth::user()->can('travel-agent')) {
            $categories = Category::whereName($request['name'])->get();

            foreach ($categories as $existCategory) {
                if ($existCategory->name == $request['name'] && $existCategory->id != $category->id)
                    return redirect()->back()->withErrors(['name' => 'The name has already been taken.']);
            }
            $this->validate($request, [
                'name' => 'required|max:20',
            ]);

            $category->name = $request['name'];
            $category->slug = str_slug($request['name'], '-');
            $category->admin_id = auth()->user()->id;
            $category->save();

            return redirect(route('category.index'));
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
            Category::whereSlug($slug)->delete();

            return redirect()->back();
        }

        else return redirect(route('admin.home'));
    }
}
