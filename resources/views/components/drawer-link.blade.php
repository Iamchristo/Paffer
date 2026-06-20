@props(['active' => false, 'icon' => 'home', 'badge' => null])

@php
$classes = $active
    ? 'flex items-center gap-3 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-medium'
    : 'flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <x-icon :name="$icon" class="h-5 w-5 shrink-0" />
    <span class="flex-1 text-sm">{{ $slot }}</span>
    @if ($badge)
        <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-semibold text-white bg-indigo-600 rounded-full">{{ $badge }}</span>
    @endif
</a>
