@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Publications</div>

                    <div class="card-body">
                        <div class="row justify-content-end pb-2">
                            <a href="{{ route('publications.create') }}" class="btn btn-primary">Add new publication</a>
                        </div>

                        <table class="table">
                            <thead>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Author</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @forelse($publications as $publication)
                                    <tr>
                                        <td>{{ $publication->title }}</td>
                                        <td>{{ $publication->content }}</td>
                                        <td>{{ $publication->author->name }}</td>
                                        <td>
                                            <a href="{{ route('publications.show', $publication->id) }}" class="btn btn-link">View more</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <h3 class="text-center">There are no amazing publications</h3>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
