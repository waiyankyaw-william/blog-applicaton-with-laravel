@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px">

        @if (session('error'))
            <div class="alert alert-warning text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">{{ $article->title }}</h5>
                <div class="card-subtitle text-muted small">
                    By {{ $article->user->name }},
                    {{ $article->created_at->diffForHumans() }},
                    Category: {{ $article->category->name }}
                </div>
                <p class="card-text">{{ $article->body }}</p>
                @auth
                    <a href="{{ url("/articles/edit/$article->id") }}" class="btn btn-warning">Edit</a>
                    <a href="{{ url("/articles/delete/$article->id") }}" class="btn btn-danger">Delete</a>
                @endauth
            </div>
        </div>

        <ul class="list-group mb-2">
            @if (count($article->comments))
                <li class="list-group-item list-group-item-secondary">Comments - {{ count($article->comments) }}</li>

                @foreach ($article->comments as $comment)
                    <li class="list-group-item">
                        @auth
                            <a href="{{ url("/comments/delete/$comment->id") }}" class="btn-close float-end"></a>
                        @endauth
                        
                        {{ $comment->content }}
                        <div>
                            By <b>{{ $comment->user->name }}</b>, {{ $comment->created_at->diffForHumans() }}
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>

        @auth
            <form action="{{ url("/comments/add") }}" method="post">
                @csrf
                <input name="article_id" type="hidden" value="{{ $article->id }}">
                <textarea name="content" id="" cols="30" rows="10" class="form-control mb-2" placeholder="New Comment"></textarea>
                <button class="btn btn-secondary w-100">Comment</button>
            </form>
        @endauth
    </div>
@endsection