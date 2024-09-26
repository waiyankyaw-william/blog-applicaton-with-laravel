<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
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
