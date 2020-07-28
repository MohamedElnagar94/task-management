@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <create-tasks-component user_id="{{\Illuminate\Support\Facades\Auth::id()}}" csrf="{{ csrf_token() }}"></create-tasks-component>
    </div>
</div>
@endsection
