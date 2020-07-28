@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Task Name</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">priority</th>
                        <th scope="col">Created at</th>
                        <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allTasks->tasks as $Task)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$Task->name}}</td>
                            <td>{{$allTasks->name}}</td>
                            <td>{{$Task->priority}}</td>
                            <td>{{$Task->created_at}}</td>
                            <td>
                                <button data-toggle="modal" data-target="#deleteTask{{$Task->id}}" class="btn btn-danger">
                                    <i class="fa fa-trash text-white"></i>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="deleteTask{{$Task->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{$Task->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to remove it?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                {!! Form::open(['route' => ['Tasks.destroy',$Task->id], 'method' => 'delete']) !!}
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th scope="row" colspan="6">
                                There is no Tasks click <a href="{{route("Tasks.create")}}">HERE</a> to create new Task <a ></a>
                            </th>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
