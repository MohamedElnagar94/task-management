@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                @error('projectName')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <form action="{{route('Project.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="projectName">Project Name</label>
                        <input type="text" name="projectName" class="form-control" id="projectName">
                        <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
