### Blog Application with Laravel

This Laravel-based blog application, developed as a class project, allows users to create, edit, and delete articles, as well as add and manage comments. The application includes features for user authentication, authorization, and pagination.

**Features:**

1. **User Authentication:**

    - Users can register and log in to the application.
    - Authentication is required for creating, editing, and deleting articles and comments.

2. **Article Management:**

    - Users can create new articles, providing a title, body, and selecting a category.
    - Users can edit or delete their own articles.
    - Articles are displayed in a paginated list on the main page.

3. **Comment Management:**

    - Users can add comments to articles.
    - Users can delete their own comments or comments on their own articles.

4. **Authorization:**
    - Users can only edit or delete their own articles.
    - Users can only delete their own comments or comments on their own articles.

**Technical Details:**

-   **Pagination:** The application uses Bootstrap for pagination, providing a user-friendly navigation through the articles.
-   **Gates:** Laravel Gates are used to define authorization logic, ensuring that users can only perform actions on resources they own.
    <br><br>

**AppServiceProvider.php**

```php
<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::define('comment-delete', function ($user, $comment) {
            return $user->id === $comment->user_id || $user->id === $comment->article->user_id;
        });

        Gate::define('article-delete', function ($user, $article) {
            return $user->id === $article->user_id;
        });
    }
}
```

<br>

**ArticleController.php**

```php
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
```

<br>

**CommentController.php**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function delete($id)
    {
        $comment = Comment::find($id);

        if (Gate::allows('comment-delete', $comment)) {
            $comment->delete();
            return back();
        } else {
            return back()->with('error', 'Unauthorize to delete this comment');
        }
    }

    public function add()
    {
        $comment = new Comment;

        $validator = validator(request()->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) return back();

        $comment->content = request()->content;
        $comment->article_id = request()->article_id;
        $comment->user_id = auth()->user()->id;
        $comment->save();

        return back();
    }
}
```
