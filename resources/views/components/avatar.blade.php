@props(['user', 'size' => 10])

@php
$path = $user?->profile?->avatar_path ?? null;
$initials = collect(explode(' ', $user?->name ?? '?'))
    ->map(fn ($part) => mb_substr($part, 0, 1))
    ->take(2)
    ->join('');
$dimension = match ((string) $size) {
    '6' => 'h-6 w-6',
    '7' => 'h-7 w-7',
    '8' => 'h-8 w-8',
    '9' => 'h-9 w-9',
    '10' => 'h-10 w-10',
    '12' => 'h-12 w-12',
    '14' => 'h-14 w-14',
    '16' => 'h-16 w-16',
    '20' => 'h-20 w-20',
    '24' => 'h-24 w-24',
    default => 'h-10 w-10',
};
@endphp

@if ($path)
    <img src="{{ Storage::url($path) }}" alt="{{ $user->name }}" {{ $attributes->merge(['class' => "{$dimension} rounded-full object-cover bg-gray-100"]) }}>
@else
    <span {{ $attributes->merge(['class' => "{$dimension} rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold uppercase"]) }}>
        {{ $initials }}
    </span>
@endif
