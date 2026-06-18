<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LearnController extends Controller
{
    public function show(Request $request, Course $course, ?Lesson $lesson = null): View
    {
        $user = $request->user();

        $isEnrolled = $user && $course->enrollments()->where('user_id', $user->id)->exists();
        $isTutor = $user && $course->tutor_id === $user->id;

        abort_unless($isEnrolled || $isTutor, 403);

        $course->load('lessons');

        $lesson ??= $course->lessons->first();

        abort_if($lesson && $lesson->course_id !== $course->id, 404);

        return view('learn.player', [
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }
}
