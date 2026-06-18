<x-install-layout subtitle="Step 4 of 4 — Demo Data">
    <h2 class="text-lg font-semibold mb-4">Load demo content?</h2>
    <p class="text-sm text-gray-600 mb-4">
        PAFFAR can populate your install with realistic demo content so you
        (or a client) can see exactly how the platform works before real
        users sign up:
    </p>

    <ul class="list-disc list-inside text-sm text-gray-600 mb-6 space-y-1">
        <li>~30 member accounts — buyers, sellers, tutors, and everyday networking members</li>
        <li>10 vendor stores, each with a full product catalog</li>
        <li>Completed, in-progress, and cancelled orders — real-looking sales history</li>
        <li>Courses with lessons, enrollments, and completions</li>
        <li>Posts, likes, comments, and a network of follows between members</li>
        <li>Reviews left on stores and courses</li>
        <li>A pending seller, a rejected seller, and a pending tutor — so the admin verification queue isn't empty</li>
    </ul>

    <p class="text-sm text-gray-600 mb-6">
        Or skip this and start with a completely empty, production-ready platform.
    </p>

    <form method="POST" action="{{ route('install.demo-data.store') }}" class="flex gap-3">
        @csrf
        <button type="submit" name="choice" value="yes"
                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
            Import Demo Data
        </button>
        <button type="submit" name="choice" value="no"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50">
            Skip — Start Fresh
        </button>
    </form>
</x-install-layout>
