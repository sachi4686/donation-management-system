<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;

class DonatorController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('donator.dashboard');
    }
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $donations = Donation::where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(10);
        $projects = Project::orderBy('created_at', 'desc')->paginate(10);
        return view('donator.Donate.Index', compact('donations', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('donator.Donate.create', compact('projects'));
    }

    public function store(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'donation_date' => 'required|date',
            'project_id' => 'nullable|exists:projects,project_id',
        ]);

        try {
            $donation = new Donation();
            $donation->user_id = auth()->id();
            $donation->amount = $request->input('amount');
            $donation->donation_date = $request->input('donation_date');
            $donation->project_id = $request->input('project_id');
            $donation->status = 'pending';

            // Check if the project exists and is of type 'cash' or 'material'
            $project = Project::find($request->project_id);

            if ($project) {
                if ($project->project_type === 'cash') {
                    // Update target amount or current amount for cash projects
                    $project->current_amount += $donation->amount;
                    // $project->progress = $this->calculateProgress($project->current_amount, $project->target_amount);
                    $project->save();
                } elseif ($project->project_type === 'material') {

                    // $project->material_quantity -= $donation->amount;
                    $project->current_quantity += $donation->amount;
                    // $project->progress = $this->calculateProgress($project->current_quantity, $project->material_quantity);
                    $project->save();
                }
            }

            // Save the donation after updating the project
            $donation->save();

            return redirect()->route('donator.donate.index')->with('success', 'Donation submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong while processing your donation. Please try again.');
        }
    }



}
