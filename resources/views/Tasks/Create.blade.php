@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <create-tasks-component user_id="{{\Illuminate\Support\Facades\Auth::id()}}"></create-tasks-component>
    </div>
</div>
@endsection
