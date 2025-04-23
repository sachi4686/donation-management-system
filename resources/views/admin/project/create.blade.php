<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-4">Create Projects</h2>

                    <form action="{{ route('admin.projects.store') }}" method="POST"  enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Project Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- project type -->
                        <div>
                            <label for="project_type" class="block font-medium text-sm text-gray-700">Project Type</label>
                            <select name="project_type" id="project_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                onchange="toggleMaterialFields()">
                                <option value="cash" {{ old('project_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="material" {{ old('project_type') == 'material' ? 'selected' : '' }}>Material</option>
                            </select>
                        </div>

                        <div id="materialFields" style="display: none;">
                            <div>
                                <label for="material_needed" class="block font-medium text-sm text-gray-700">Material Needed</label>
                                <input type="text" name="material_needed" id="material_needed"
                                    value="{{ old('material_needed') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="material_quantity" class="block font-medium text-sm text-gray-700">Material Quantity</label>
                                <input type="number" name="material_quantity" id="material_quantity"
                                    value="{{ old('material_quantity') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>



                        <!-- Description -->
                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Amount -->
                        <div>
                            <label for="target_amount" class="block font-medium text-sm text-gray-700">Target
                                Amount</label>
                            <input type="number" name="target_amount" id="target_amount" step="0.01"
                                value="{{ old('target_amount', 0) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('target_amount')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Amount -->
                        <div>
                            <label for="current_amount" class="block font-medium text-sm text-gray-700">Current
                                Amount</label>
                            <input type="number" name="current_amount" id="current_amount" step="0.01"
                                value="{{ old('current_amount', 0) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('current_amount')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block font-medium text-sm text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('start_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block font-medium text-sm text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('end_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Image -->
                        <div>
                            <label for="image" class="block font-medium text-sm text-gray-700">Project Image</label>
                            <input type="file" name="image" id="image"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Submit -->
                        <div>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Save Project
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleMaterialFields() {
            const projectType = document.getElementById('project_type').value;
            const materialFields = document.getElementById('materialFields');
            if (projectType === 'material') {
                materialFields.style.display = 'block';
            } else {
                materialFields.style.display = 'none';
            }
        }

        // Ensure it runs on page load
        document.addEventListener('DOMContentLoaded', function () {
            toggleMaterialFields();
        });
    </script>
</x-app-layout>


