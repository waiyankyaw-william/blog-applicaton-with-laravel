<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth')->except('index', 'detail');
    }

    public function index()
    {
        $articles = Article::latest()->paginate(5);

        return view('articles.index', [
            'articles' => $articles,
        ]);
    }

    public function detail($id)
    {
        $article = Article::find($id);

        return view('articles.detail', [
            'article' => $article
        ]);
    }

    public function add()
    {
        $categories = Category::all();

        return view('articles.add', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $article = new Article;

        $validator = validator(request()->all(), [
            'title' => 'required',
            'body' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;
        $article->user_id = auth()->user()->id;
        $article->save();

        return redirect("/articles")->with('info', 'Article created');
    }

    public function delete($id)
    {
        $article = Article::find($id);

        if (Gate::allows('article-delete', $article)) {
            $article->delete();
            return redirect("/articles")->with('info', 'Article deleted');
        } else {
            return back()->with('error', 'Unauthorize to delete this article');
        }
    }

    public function edit($id)
    {
        $article = Article::find($id);
        $categories = Category::all();

        if (Gate::allows('article-delete', $article)) {
            return view('articles.edit', [
                'article' => $article,
                'categories' => $categories,
            ]);
        } else {
            return back()->with('error', 'Unauthorize to edit this article');
        }
    }

    public function update($id)
    {
        $article = Article::find($id);

        $validator = validator(request()->all(), [
            'title' => 'required',
            'body' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;
        $article->save();

        return redirect("/articles/detail/$article->id")->with('info', 'Article updated');
    }
}
