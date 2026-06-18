<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manage Course') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs uppercase tracking-wide text-gray-500">{{ __('Status') }}: {{ $course->status }}</span>
                    @if ($course->status === 'draft')
                        <form method="POST" action="{{ route('tutor.courses.submit', $course) }}">
                            @csrf
                            <x-secondary-button type="submit">{{ __('Submit for Review') }}</x-secondary-button>
                        </form>
                    @endif
                </div>

                <form method="POST" action="{{ route('tutor.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $course->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description', $course->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="price_cents" :value="__('Price (cents, 0 = free)')" />
                        <x-text-input id="price_cents" name="price_cents" type="number" min="0" class="mt-1 block w-full" :value="old('price_cents', $course->price_cents)" required />
                        <x-input-error :messages="$errors->get('price_cents')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="cover" :value="__('Cover Image')" />
                        <input id="cover" name="cover" type="file" class="mt-1 block w-full text-sm" />
                        <x-input-error :messages="$errors->get('cover')" class="mt-2" />
                    </div>

                    <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Lessons') }}</h3>

                <div class="space-y-3">
                    @foreach ($course->lessons as $lesson)
                        <div class="border border-gray-100 rounded-md p-4">
                            <form method="POST" action="{{ route('tutor.lessons.update', [$course, $lesson]) }}" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <x-text-input name="title" type="text" class="block w-full" value="{{ $lesson->title }}" required />
                                <x-text-input name="video_url" type="url" class="block w-full" value="{{ $lesson->video_url }}" placeholder="{{ __('Video URL (optional)') }}" />
                                <textarea name="content" rows="3" class="block w-full rounded-md border-gray-300">{{ $lesson->content }}</textarea>
                                <div class="flex justify-end gap-3">
                                    <button type="submit" class="text-sm text-indigo-600">{{ __('Save') }}</button>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('tutor.lessons.destroy', [$course, $lesson]) }}" onsubmit="return confirm('{{ __('Delete this lesson?') }}')" class="mt-2 text-right">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500">{{ __('Delete Lesson') }}</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('tutor.lessons.store', $course) }}" class="mt-4 space-y-2 border-t border-gray-100 pt-4">
                    @csrf
                    <x-input-label :value="__('Add Lesson')" />
                    <x-text-input name="title" type="text" class="block w-full" placeholder="{{ __('Lesson title') }}" required />
                    <x-text-input name="video_url" type="url" class="block w-full" placeholder="{{ __('Video URL (optional)') }}" />
                    <textarea name="content" rows="3" class="block w-full rounded-md border-gray-300" placeholder="{{ __('Lesson content') }}"></textarea>
                    <x-primary-button>{{ __('Add Lesson') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
