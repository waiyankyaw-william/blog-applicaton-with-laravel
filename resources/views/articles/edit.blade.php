@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px">
        <form method="post">
            @csrf

            <div class="mb-3">
                <label for="">Title</label>
                <input name="title" type="text" class="form-control" value="{{ $article->title }}">
            </div>
    
            <div class="mb-3">
                <label for="">Body</label>
                <textarea name="body" id="" cols="30" rows="10" class="form-control">{{ $article->body }}</textarea>
            </div>
    
            <div class="mb-3">
                <label for="">Category</label>
                <select name="category_id" id="" class="form-select">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($article->category_id === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
    
            <button class="btn btn-primary w-100">Update</button>
        </form>
    </div>
@endsection