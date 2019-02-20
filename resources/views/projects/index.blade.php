@extends('layouts.app')

@section('content')

    <div class="flex items-center mb-3">

        <a href="/projects/create">New Project</a>

    </div>

    <div class="flex">

        @forelse($projects as $project)
        <div class="bg-white mr-4 p-4 rounded shadow w-1/3" style="height: 250px">

            <h3 class="text-xl mb-4">{{ $project->title }}</h3>

            <div>{{ str_limit($project->description, 325) }}</div>

        </div>
        {{--If the projects array is empty --}}
        @empty

        <div>No projects yet.</div>
        @endforelse

    </div>



@endsection
