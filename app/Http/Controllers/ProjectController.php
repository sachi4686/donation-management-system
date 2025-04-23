<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('admin.project.index', compact('projects'));
    }
    public function create()
    {
        return view('admin.project.create');
    }
    public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'target_amount' => 'nullable|numeric',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'image' => 'nullable|max:2048',
        'material_needed' => 'nullable|string|max:255', // add validation for material_needed
        'material_quantity' => 'nullable|numeric', // add validation for material_quantity
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('projects', 'public');
    }

    $project = new Project();
    $project->name = $request->name;
    $project->project_type = $request->project_type;
    $project->description = $request->description;
    $project->start_date = $request->start_date;
    $project->end_date = $request->end_date;
    $project->image = $imagePath;

    if ($request->project_type === 'cash') {
        $project->target_amount = $request->target_amount ?? 0;
        $project->current_amount = 0;
        $project->progress = $this->calculateProgress(0, $request->target_amount);
    } else {
        $project->material_needed = $request->material_needed;
        $project->material_quantity = $request->material_quantity;
        $project->current_quantity = 0;
        $project->progress = $this->calculateProgress(0, $request->material_quantity);
    }

    $project->save();
    return redirect()->route('projects.index')->with('success', 'Project created successfully.');
}


    private function calculateProgress($current, $total)
    {
        if ($total == 0) {
            return 0;
        }
        return round(($current / $total) * 100, 2);
    }

    public function edit(Project $project)
    {
        return view('admin.project.edit', compact('project'));
    }
    public function update(Request $request, Project $project)
{
    // Validate fields based on project type
    $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'image' => 'nullable|max:2048',
        'project_type' => 'required|in:cash,material',
    ];

    if ($request->project_type === 'cash') {
        $rules['target_amount'] = 'required|numeric|min:0';
        $rules['current_amount'] = 'required|numeric|min:0';
    } elseif ($request->project_type === 'material') {
        $rules['material_quantity'] = 'required|numeric|min:0';
    }

    // Validate the request with dynamic rules based on project type
    $this->validate($request, $rules);

    // Calculate progress for cash projects
    if ($request->project_type === 'cash') {
        $progress = $this->calculateProgress($request->current_amount, $request->target_amount);
    } elseif ($request->project_type === 'material') {
        // For material projects, you may want to calculate progress differently
        // Example: $progress = ($request->material_quantity / $project->target_quantity) * 100;
        $progress = 0; // Replace with your logic if needed
    }

    // Handle the image upload if there is a new image
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($project->image && \Storage::disk('public')->exists($project->image)) {
            \Storage::disk('public')->delete($project->image);
        }

        // Store the new image and update the path
        $project->image = $request->file('image')->store('projects', 'public');
    }

    // Update project fields
    $project->name = $request->name;
    $project->description = $request->description;
    $project->target_amount = $request->target_amount; // Only update this if project type is cash
    $project->current_amount = $request->current_amount; // Only update this if project type is cash
    $project->material_quantity = $request->material_quantity; // Only update this if project type is material
    $project->start_date = $request->start_date;
    $project->end_date = $request->end_date;
    $project->project_type = $request->project_type; // Update the project type
    $project->progress = $progress; // Update the calculated progress
    $project->save();

    return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
}

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
