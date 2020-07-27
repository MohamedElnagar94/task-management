@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <form action="" method="post">
                    @csrf
                    <label for="projectName">
                        <input type="text" id="projectName" placeholder="Project Name" class="form-control">
                    </label>
                    <input type="submit" value="save" class="btn btn-primary">
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
