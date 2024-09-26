@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px">

        <div class="d-flex justify-content-center">{{ $articles->links() }}</div>

        @if (session('info'))
            <div class="alert alert-info text-center">
                {{ session('info') }}
            </div>
        @endif

        @foreach ($articles as $article)
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{ $article->title }}</h5>
                    <div class="card-subtitle text-muted small">
                        By {{ $article->user->name }},
                        {{ $article->created_at->diffForHumans() }},
                        Category: {{ $article->category->name }}
                    </div>
                    <p class="card-text">{{ $article->body }}</p>
                    <a href="{{ url("/articles/detail/$article->id") }}" class="card-link">Details &raquo;</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection