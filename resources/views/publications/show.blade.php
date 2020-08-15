@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>{{ $publication->title }}</h1>
        <hr>
        <p class="text-justify">{{ $publication->content }}</p>
        <div class="card mt-5">
            <div class="card-header">
                Comments
            </div>
            <ul class="list-group list-group-flush">
                @forelse($publication->comments as $comment)
                    <li class="list-group-item">
                        <p class="text-primary">{{ $comment->author->name }}</p>
                        <p>{{ $comment->content }}</p>
                    </li>
                @empty
                    <li class="list-group-item">Publication has no comments</li>
                @endforelse
            </ul>
            @if(Auth::user()->can('comment-publication', $publication))
                <form action="{{ route('publications.comment', $publication->id) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" id="content" required class="form-control" autocomplete="off" placeholder="Add a comment"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <input type="submit" value="Send" class="btn btn-success">
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
