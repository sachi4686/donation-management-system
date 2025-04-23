<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class DonationController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('donations.Index', compact('projects'));
    }

    public function store(Request $request)
    {
        \Log::info($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'amount' => 'required|numeric|min:0.01',
            'donation_date' => 'required|date',
            'project_id' => 'nullable|exists:projects,project_id',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password'=> bcrypt('12345678'),
            ]);
            $role = Role::findByName('Donator');
            $user->assignRole($role);

            Auth::login($user);

            $donation = new Donation();
            $donation->user_id = $user->id;
            $donation->status = 'pending';
            $donation->amount = $request->amount;
            $donation->donation_date = $request->donation_date;
            $donation->project_id = $request->project_id;

            $project = Project::find($request->project_id);

            if ($project) {
                if ($project->project_type === 'cash') {
                    $project->current_amount += $donation->amount;
                    $project->save();
                } elseif ($project->project_type === 'material') {

                    $project->current_quantity += $donation->amount;
                    $project->save();
                }
            }
            $donation->save();

            if ($user->hasRole('Donator')) {
                return redirect()->route('donator.dashboard');
            }
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->back()->with('error', 'An error occurred while processing your donation.');
        }
    }
}
