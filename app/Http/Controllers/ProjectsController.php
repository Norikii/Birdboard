<?php

namespace Birdboard\Http\Controllers;

use Birdboard\Project;
use Illuminate\Http\Request;


class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // received argument from a request {project}
        // will be passed to find a particular project by its id
        // if not found exception is thrown

        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        // if authenticated user is not the same as the owner_id of the project we want to display
//        if (auth()->id() !== $project->owner_id)
//        {
//            // we can redirect of abort or what ever
//            abort(403);
//        }


        return view('projects.show', compact('project'));
    }

    public function create()
    {

        return view('projects.create');
    }

    public function store()
    {
        // validate
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

//        $attributes['owner_id'] = auth()->id();

        auth()->user()->projects()->create($attributes);

        // Persist
//        Project::create($attributes);

        // redirect
        return redirect('/projects');
    }
}
