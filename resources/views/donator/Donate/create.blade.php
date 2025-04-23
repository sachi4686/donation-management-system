@extends('layouts.donator')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold mb-4">Create Donations</h2>
            @if (session('success'))
                <x-notification type="success" :message="session('success')" />
            @endif

            @if (session('error'))
                <x-notification type="error" :message="session('error')" />
            @endif
        </div>
    </div>
    <form action="{{ route('donator.donate.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Project Selection --}}
        <div>
            <label for="project_id" class="block font-medium text-gray-700">Select Project</label>
            <select name="project_id" id="project_id"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm h-10 px-3" required>
                <option value="">Choose a Project</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->project_id }}" data-type="{{ $project->project_type }}">
                        {{ $project->name }} ({{ ucfirst($project->project_type) }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Amount --}}
        <div>
            <label for="amount" class="block font-medium text-gray-700">Amount</label>
            <input type="number" name="amount" id="amount" min="1" step="0.01" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>

        {{-- Donation Date --}}
        <div>
            <label for="donation_date" class="block font-medium text-gray-700">Donation Date</label>
            <input type="date" name="donation_date" id="donation_date" value="{{ now()->format('Y-m-d') }}"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Submit Donation
            </button>
        </div>
    </form>

    {{-- JavaScript to show/hide fields based on project type --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectSelect = document.getElementById('project_id');
            const amountInput = document.getElementById('amount');

            // Listen for project selection change
            projectSelect.addEventListener('change', function() {
                const selectedOption = projectSelect.options[projectSelect.selectedIndex];
                const projectType = selectedOption.getAttribute('data-type');

                // Handle changes based on project type (cash/material)
                if (projectType === 'cash') {
                    // Adjust form for cash type project if needed
                    amountInput.setAttribute('min', 1); // Ensure minimum donation for cash project
                } else if (projectType === 'material') {
                    // Adjust form for material type project if needed
                    amountInput.setAttribute('min', 1); // Ensure minimum donation for material project
                }
            });
        });
    </script>
@endsection
