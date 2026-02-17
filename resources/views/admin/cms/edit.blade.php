@extends('layouts.admin')

@section('title', 'Edit ' . $sectionName . ' | CMS')

@section('page-title', 'Edit ' . $sectionName)

@section('content')

<div class="max-w-4xl">
    <form action="{{ route('admin.cms.update', $section) }}" method="POST" enctype="multipart/form-data" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @foreach($fields as $index => $field)
            @php
                $existingContent = $contents->where('key', $field['key'])->first();
                $value = old("contents.{$index}.value", $existingContent->value ?? '');
            @endphp

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">
                    {{ $field['label'] }}
                </label>

                <input type="hidden" name="contents[{{ $index }}][key]" value="{{ $field['key'] }}">
                <input type="hidden" name="contents[{{ $index }}][type]" value="{{ $field['type'] }}">

                @if($field['type'] === 'text' || $field['type'] === 'url')
                    <input type="{{ $field['type'] === 'url' ? 'url' : 'text' }}" 
                           name="contents[{{ $index }}][value]" 
                           value="{{ $value }}"
                           class="input-dark"
                           placeholder="Enter {{ strtolower($field['label']) }}">

                @elseif($field['type'] === 'textarea')
                    <textarea name="contents[{{ $index }}][value]" 
                              rows="4" 
                              class="input-dark"
                              placeholder="Enter {{ strtolower($field['label']) }}">{{ $value }}</textarea>

                @elseif($field['type'] === 'image')
                    <!-- Current Image Preview -->
                    @if($value)
                    <div class="mb-3 bg-dark-800 p-4 rounded-lg">
                        <p class="text-gray-400 text-sm mb-2">Current Image:</p>
                        <img src="{{ asset('storage/' . $value) }}" 
                             alt="{{ $field['label'] }}" 
                             class="max-w-xs h-auto rounded border border-white/10">
                    </div>
                    @endif

                    <!-- File Upload -->
                    <input type="file" 
                           name="contents[{{ $index }}][value]" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-gold-500 file:text-dark-900
                                  hover:file:bg-gold-600
                                  file:cursor-pointer
                                  border border-white/10 rounded bg-dark-800 p-2">
                    <p class="text-xs text-gray-500 mt-1">
                        Upload new image to replace current one (Max 5MB, JPG/PNG)
                    </p>

                    @if($value)
                        <!-- Keep existing image if no new upload -->
                        <input type="hidden" name="contents[{{ $index }}][existing_value]" value="{{ $value }}">
                    @endif
                @endif

                @error("contents.{$index}.value")
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endforeach

            @if(count($fields) === 0)
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-4xl text-gray-600 mb-4 block"></i>
                <p class="text-gray-400">No fields configured for this section yet.</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Save Changes
            </button>
            <a href="{{ route('admin.cms.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
            <a href="{{ route('home') }}" target="_blank" class="ml-auto bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i> Preview Frontend
            </a>
        </div>
    </form>
</div>

@endsection
