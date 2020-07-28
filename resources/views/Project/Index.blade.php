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
                        <th scope="col">Project Name</th>
                        <th scope="col">User Name</th>
                        <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td><a href="{{route('Project.show',$project->id)}}">{{$project->name}}</a></td>
                            <td>{{$project->userName->name}}</td>
                            <td><a href="{{route('Project.edit',$project->id)}}" class="btn btn-success"><i class="fa fa-edit text-white"></i></a></td>
                            <td>
                                <button data-toggle="modal" data-target="#deleteProject{{$project->id}}" class="btn btn-danger">
                                    <i class="fa fa-trash text-white"></i>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="deleteProject{{$project->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{$project->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to remove it?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                {!! Form::open(['route' => ['Project.destroy',$project->id], 'method' => 'delete']) !!}
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
                            <th scope="row" colspan="5">
                                There is no projects click <a href="{{route("Project.create")}}">HERE</a> to create new project <a ></a>
                            </th>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
