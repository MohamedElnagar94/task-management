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
                {!! Form::open(['route' => ['Project.update',$editProject[0]->id], 'method' => 'put']) !!}
                <div class="form-group">
                    {!! Form::label('projectName', 'Project Name') !!}
                    {!! Form::text('projectName', $editProject[0]->name ,['class' => 'form-control','id' => 'projectName']) !!}
                </div>
                {!! Form::submit('Save',['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
