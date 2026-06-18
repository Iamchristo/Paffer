<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $enrollments = $request->user()->enrollments()->with('course.tutor')->latest()->paginate(10);

        return view('learn.my-courses', [
            'enrollments' => $enrollments,
        ]);
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->isApproved(), 404);

        $request->user()->enrollments()->firstOrCreate(['course_id' => $course->id]);

        return redirect()->route('learn.show', $course)->with('status', 'enrolled');
    }
}
