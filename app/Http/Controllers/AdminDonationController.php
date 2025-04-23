<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminDonationController extends Controller
{
    public function index()
    {
        $donations = Donation::all();
        $projects = Project::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.donations.index', compact('donations', 'projects'));
    }

    public function approve($donation_id)
    {
        $donations = Donation::where('donation_id', $donation_id)->first();

        if ($donations) {
            if ($donations->status == 'completed') {
                return redirect()->back()->with('error', 'Donation already approved.');
            }
            $donations->status = 'completed';
            $donations->save();

            $project = Project::find($donations->project_id);
            if ($project->project_type == 'cash') {
                $project->current_amount += $donations->amount;

                $project->progress = Donation::calculateProgress($project->current_amount, $project->target_amount);
                $project->save();
            } elseif ($project->project_type === 'material') {
                $project->current_quantity += $donations->amount;
                $project->progress = Donation::calculateProgress($project->current_quantity, $project->material_quantity);
                $project->save();
            }
            return redirect()->back()->with('success', 'Donation approved successfully.');
        } else {
            return redirect()->back()->with('error', 'Donation not found.');
        }

    }

}
