@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px">
        @if ($errors->any())
            <div class="alert alert-warning text-center">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="post">
            @csrf
            <div class="mb-3">
                <label>Title</label>
                <input type="text" class="form-control" name="title">
            </div>

            <div class="mb-3">
                <label>Body</label>
                <textarea name="body" id="" cols="30" rows="5" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" id="" class="form-select">
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary w-100">Post</button>
        </form>
    </div>
@endsection