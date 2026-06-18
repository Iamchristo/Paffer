<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($request, $course);

        $validated = $this->validated($request);

        $course->lessons()->create([
            ...$validated,
            'position' => $course->lessons()->max('position') + 1,
        ]);

        return redirect()->route('tutor.courses.edit', $course)->with('status', 'lesson-added');
    }

    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorizeOwner($request, $course);
        abort_unless($lesson->course_id === $course->id, 404);

        $lesson->update($this->validated($request));

        return redirect()->route('tutor.courses.edit', $course)->with('status', 'lesson-updated');
    }

    public function destroy(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorizeOwner($request, $course);
        abort_unless($lesson->course_id === $course->id, 404);

        $lesson->delete();

        return redirect()->route('tutor.courses.edit', $course)->with('status', 'lesson-deleted');
    }

    private function authorizeOwner(Request $request, Course $course): void
    {
        abort_unless($course->tutor_id === $request->user()->id, 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:255'],
        ]);
    }
}
