@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create Publication
                    </div>
                    <div class="card-body">
                        <form action="{{ route('publications.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="titles">Title</label>
                                <input type="text" name="title" required class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <input type="text" name="content" required class="form-control" autocomplete="off">
                            </div>
                            <div class="d-flex justify-content-end">
                                <input type="submit" value="Send" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
