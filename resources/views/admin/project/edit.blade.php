<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-4">Edit Project</h2>

                    <form action="{{ route('admin.projects.update', $project->project_id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Project Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $project->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Type -->
                        <div>
                            <label for="project_type" class="block font-medium text-sm text-gray-700">Project Type</label>
                            <select name="project_type" id="project_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="toggleFields()">
                                <option value="cash" {{ old('project_type', $project->project_type) === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="material" {{ old('project_type', $project->project_type) === 'material' ? 'selected' : '' }}>Material</option>
                            </select>
                            @error('project_type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Amount (For Cash Projects) -->
                        <div id="target_amount_field" class="{{ $project->project_type !== 'cash' ? 'hidden' : '' }}">
                            <label for="target_amount" class="block font-medium text-sm text-gray-700">Target Amount</label>
                            <input type="number" name="target_amount" id="target_amount" step="0.01"
                                value="{{ old('target_amount', $project->target_amount) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('target_amount')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Amount (For Cash Projects) -->
                        <div id="current_amount_field" class="{{ $project->project_type !== 'cash' ? 'hidden' : '' }}">
                            <label for="current_amount" class="block font-medium text-sm text-gray-700">Current Amount</label>
                            <input type="number" name="current_amount" id="current_amount" step="0.01"
                                value="{{ old('current_amount', $project->current_amount) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('current_amount')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Material Quantity (For Material Projects) -->
                        <div id="material_quantity_field" class="{{ $project->project_type !== 'material' ? 'hidden' : '' }}">
                            <label for="material_quantity" class="block font-medium text-sm text-gray-700">Material Quantity</label>
                            <input type="number" name="material_quantity" id="material_quantity" step="0.01"
                                value="{{ old('material_quantity', $project->material_quantity) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('material_quantity')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block font-medium text-sm text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', \Carbon\Carbon::parse($project->start_date)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                            @error('start_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block font-medium text-sm text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', \Carbon\Carbon::parse($project->end_date)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                            @error('end_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Image -->
                        <div>
                            <label for="image" class="block font-medium text-sm text-gray-700">Upload New Image
                                (optional)</label>
                            <input type="file" name="image" id="image"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            @if ($project->image)
                                <div class="mt-4">
                                    <p class="font-medium text-sm text-gray-700 mb-2">Current Image:</p>
                                    <img src="{{ asset('storage/' . $project->image) }}" alt="Current Project Image"
                                        class="w-48 rounded shadow border">
                                </div>
                            @endif
                        </div>

                        <!-- Submit -->
                        <div>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Save Changes
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle visibility of fields based on selected project type
        function toggleFields() {
            const projectType = document.getElementById('project_type').value;
            document.getElementById('target_amount_field').classList.toggle('hidden', projectType !== 'cash');
            document.getElementById('current_amount_field').classList.toggle('hidden', projectType !== 'cash');
            document.getElementById('material_quantity_field').classList.toggle('hidden', projectType !== 'material');
        }

        // Call toggleFields on page load to show the correct fields
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>

</x-app-layout>
