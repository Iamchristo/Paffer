<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        $courses = Course::query()
            ->where('status', 'approved')
            ->when($search, fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->with('tutor')
            ->withCount('lessons')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('learn.catalog', [
            'courses' => $courses,
            'search' => $search,
        ]);
    }

    public function show(Request $request, Course $course): View
    {
        abort_unless($course->isApproved(), 404);

        $course->load(['tutor.profile', 'lessons', 'reviews.user']);

        $isEnrolled = $request->user()
            && $course->enrollments()->where('user_id', $request->user()->id)->exists();

        return view('learn.course-show', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
        ]);
    }
}
