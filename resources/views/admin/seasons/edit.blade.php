@extends('layouts.layout')
@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Season</h3>
        </div>
        <div>
            <form action="{{ route('admin.seasons.update', $season->uuid) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season Type
                            *</label>
                        <select name="type" id="type" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="{{ $season->type }}">Select</option>
                            <option value="dry" {{ old('type') == 'dry' ? 'selected' : $season->type}}>Dry</option> }}>Dry</option>
                            <option value="wet" {{ old('type') == 'wet' ? 'selected' : $season->type }}>Wet</option>
                        </select>
                    </div>
                    <div>
                        <label for="seasonName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season
                            Name
                            *</label>
                        <input type="text" id="seasonName" name="name" value="{{ old('name', $season->name) }}"
                            required placeholder="e.g: 2025 Dry Season"
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                            Date *</label>
                        <input type="date" id="startDate" name="start_date"
                            value="{{ old('start_date', $season->start_date) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                            Date *</label>
                        <input type="date" id="endDate" name="end_date"
                            value="{{ old('end_date', $season->end_date) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                    <div>
                        <label for="collection_start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Collection Start
                            Date *</label>
                        <input type="date" id="collection_start_date" name="collection_start_date" value="{{ old('collection_start_date', $season->collection_start_date) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('collection_start_date')" class="mt-2" />
                    </div>
                    <div>
                        <label for="collection_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Collection End
                            Date *</label>
                        <input type="date" id="collection_end_date" name="collection_end_date" value="{{ old('collection_end_date', $season->collection_end_date) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('collection_end_date')" class="mt-2" />
                    </div>
                    <div>
                        <label for="returnDeadline"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Return Deadline
                            *</label>
                        <input type="date" id="returnDeadline" name="return_deadline"
                            value="{{ old('return_deadline', $season->return_deadline) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('return_deadline')" class="mt-2" />
                    </div>
                    <div>
                        <label for="insuranceRate"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Insurance Rate (%)
                            *</label>
                        <input type="number" id="insuranceRate" min="0" max="100" step="0.1"
                            value="{{ old('insurance_rate', $season->insurance_rate) }}" name="insurance_rate" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('insurance_date')" class="mt-2" />
                    </div>
                    <div>
                        <label for="reminderDays"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reminder Days After
                            Deadline *</label>
                        <input type="number" id="reminderDays" name="send_reminder_after_days" min="1"
                            value="{{ old('send_reminder_after_days', $season->send_reminder_after_days) }}" required
                            placeholder="e.g: 3"
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('send_reminder_after_days')" class="mt-2" />
                    </div>
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total
                            Budget (₦)</label>
                        <input type="number" id="budget" name="budget" value="{{ old('budget ', $season->budget) }}"
                            placeholder="e.g: 5000000" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="commodities"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodities *</label>
                        <select id="commodities" name="commodities[]" multiple required
                            class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            @foreach ($commodities as $item)
                                <option value="{{ $item['id'] }}" @if (in_array($item['id'], old('commodities', $selected))) selected @endif>
                                    {{ $item['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hold Ctrl or Cmd to select multiple
                            <x-input-error :messages="$errors->get('commodities')" class="mt-2" />
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">
                        Update Season
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
