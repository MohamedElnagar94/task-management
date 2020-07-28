<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with('userName')->where('user_id',Auth::id())->get();
        // dd($projects);
        return view('Project.Index',['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Project.Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'projectName' => ['required', 'string', 'max:150','min:3'],
        ],[
            'projectName.required' => 'The Project Name Field is required',
            'projectName.max' => 'The Project Name should be more than 3 character and less than 150 character',
            'projectName.min' => 'The Project Name should be more than 3 character and less than 150 character',
        ]);
        Project::create([
            'name' => $request->projectName,
            'user_id' => Auth::id()
        ]);
        return redirect("/Project")->with('success', 'Project has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $allTasks = Project::with("tasks")->where([['id' , $id],['user_id' , Auth::id()]])->first();
        // dd($allTasks);
        return view('Tasks.index',['allTasks' => $allTasks]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $editProject = Project::where([['id' , $id],['user_id' , Auth::id()]])->get();
        // dd($editPhone->isEmpty());
        if ($editProject->isEmpty()) {
            return redirect("/Project");
        }
        return view("Project.Edit",['editProject' => $editProject]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = Project::findOrFail($id);
        // dd($request->all());
        $request->validate([
            'projectName' => ['required', 'string', 'max:150','min:3'],
        ],[
            'projectName.required' => 'The Project Name Field is required',
            'projectName.max' => 'The Project Name should be more than 3 character and less than 150 character',
            'projectName.min' => 'The Project Name should be more than 3 character and less than 150 character',
        ]);

        $update->name = $request->projectName;
        $update->save();
        return redirect("/Project")->with('success', 'Project has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Project::findOrFail($id);
        $destroy->delete();
        return redirect("/Project");
    }
}
