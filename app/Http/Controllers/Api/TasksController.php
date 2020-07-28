<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Project;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $max = Task::where([['user_id',$request->user_id],['project_id',$request->projectID]])->max('priority');
        Task::create([
            'project_id' => $request->projectID,
            'name' => $request->taskName,
            'priority' => $max + 1,
            'user_id' => $request->user_id
        ]);
        $projectTasks = Task::where([['user_id',$request->user_id],['project_id',$request->projectID]])->get();
        return response()->json(["status" => "200","projectTasks" => $projectTasks],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $update = Task::findOrFail($request->taskID);
        $update->name = $request->newTask;
        $update->save();
        $projects = Project::with('userName')->with('tasks')->where('user_id',$request->user_id)->get();
        return response()->json(["status" => "200",'projects' => $projects],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function taskOrder(Request $request)
    {
        $priority = array();
        for ($i=0; $i < count($request->taskOrder); $i++) {
            array_push($priority,$request->taskOrder[$i]['priority']);
        }
        sort($priority);
        for ($i=0; $i < count($request->taskOrder); $i++) {
            $update = Task::findOrFail($request->taskOrder[$i]['id']);
            $update->priority = $priority[$i];
            $update->save();
        }
        $projects = Project::with('userName')->with('tasks')->where('user_id',$request->user_id)->get();
        return response()->json(["status" => "200",'projects' => $projects],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $destroy = Task::findOrFail($request->taskID);
        $destroy->delete();
        $projects = Project::with('userName')->with('tasks')->where('user_id',$request->user_id)->get();
        return response()->json(["status" => "200",'projects' => $projects],200);
    }
}
