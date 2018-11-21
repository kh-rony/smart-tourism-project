<?php

namespace App\Http\Controllers\Admin\Article;

use App\Model\Admin\Article\Article;
use App\Model\Admin\Place\Category;
use App\Model\Admin\Place\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
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

            $unpublishArticles = Article::wherePublished(0)->get();
            $articles = Article::wherePublished(1)->get();
            return view('admin.article.show', compact('unpublishArticles', 'articles'));
        }

        else if (Auth::user()->can('article-admin')) {

            $articles = Article::whereAdminId(auth()->user()->id)->get();
            return view('admin.article.show', compact('articles'));
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
        if (Auth::user()->can('article-admin')) {
            $categories = Category::all();
            $tags = Tag::all();

            return view('admin.article.create', compact('categories', 'tags'));
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
        if (Auth::user()->can('article-admin')) {
            $this->validate($request, [
                'title' => 'required|max:255|unique:articles',
                'content' => 'required',
                'images' => 'required',
                'categories' => 'required',
                'tags' => 'required'
            ]);

            $article = Article::create([
                'title' => $request['title'],
                'slug' => str_slug($request['title'], '-'),
                'content' => $request['content']
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos');
                    $article->photos()->create(['img_url' => $imgUrl]);
                }
            }

            $article->admin_id = auth()->user()->id;

            $categoryId = [];
            foreach ($request->categories as $category) {
                $existCategory = Category::whereSlug($category)->first();
                array_push($categoryId, $existCategory->id);
            }

            $article->categories()->sync($categoryId);

            $tagId = [];
            foreach ($request->tags as $tag) {
                $existTag = Tag::whereSlug($tag)->first();
                array_push($tagId, $existTag->id);
            }

            $article->tags()->sync($tagId);

            $article->save();


            return redirect(route('article.index'));
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
    public function edit(Article $article)
    {
        //
        if ((Auth::user()->can('article-admin') && auth()->user()->id == $article->admin_id) || Auth::user()->can('super-admin')) {
            $categories = Category::all();
            $tags = Tag::all();

            return view('admin.article.edit', compact('article','categories', 'tags'));
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
    public function update(Request $request, Article $article)
    {
        //
        if ((Auth::user()->can('article-admin') && auth()->user()->id == $article->admin_id) || Auth::user()->can('super-admin')) {
            $articles = Article::whereTitle($request['title'])->get();

            foreach ($articles as $existArticle) {
                if ($existArticle->title == $request['title'] && $existArticle->id != $article->id)
                    return redirect()->back()->withErrors(['title' => 'The title has already been taken.']);
            }
            $this->validate($request, [
                'title' => 'required|max:255',
                'content' => 'required',
//                'img_url' => 'required',
                'categories' => 'required',
                'tags' => 'required'
            ]);

            $article->title = $request['title'];
            $article->slug = str_slug($request['title'], '-');
            $article->content = $request['content'];

            /*        if ($request->hasFile('img_url')) {
                        foreach ($request->img_url as $url) {
                            $img = $url->store('public/photos');
                            $place->photos()->update(['img_url' => $img]);
                        }
                    }*/

            $categoryId = [];
            foreach ($request->categories as $category) {
                $existArticle = Category::whereSlug($category)->first();
                array_push($categoryId, $existArticle->id);
            }

            $article->categories()->sync($categoryId);

            $tagId = [];
            foreach ($request->tags as $tag) {
                $existTag = Tag::whereSlug($tag)->first();
                array_push($tagId, $existTag->id);
            }

            $article->tags()->sync($tagId);

            if (Auth::user()->can('super-admin')) {
                $article->published = 1;
            } else {
                $article->published = 0;
            }

            $article->save();


            return redirect(route('article.index'));
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
        $article = Article::whereSlug($slug)->first();

        if ((Auth::user()->can('article-admin') && auth()->user()->id == $article->admin_id) || Auth::user()->can('super-admin')) {
            $article->delete();

            return redirect()->back();
        }

        else return redirect(route('admin.home'));
    }

    public function publish(Article $article) {

        if (Auth::user()->can('super-admin')) {
            $article->published = 1;
            $article->published_by = Auth::user()->id;
            $article->save();
            return redirect()->back();
        }

        return redirect(route('admin.home'));
    }
}
