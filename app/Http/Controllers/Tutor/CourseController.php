<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()->withCount(['lessons', 'enrollments'])->latest()->paginate(10);

        return view('tutor.courses.index', [
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        return view('tutor.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $course = $request->user()->courses()->create([
            ...$validated,
            'slug' => Str::slug($validated['title']).'-'.Str::random(6),
            'cover_path' => $request->file('cover')?->store('courses', 'public'),
            'status' => 'draft',
        ]);

        return redirect()->route('tutor.courses.edit', $course)->with('status', 'course-created');
    }

    public function edit(Request $request, Course $course): View
    {
        $this->authorizeOwner($request, $course);

        $course->load('lessons');

        return view('tutor.courses.edit', [
            'course' => $course,
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($request, $course);

        $validated = $this->validated($request);

        $course->fill($validated);

        if ($request->hasFile('cover')) {
            $course->cover_path = $request->file('cover')->store('courses', 'public');
        }

        $course->save();

        return redirect()->route('tutor.courses.edit', $course)->with('status', 'course-updated');
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($request, $course);

        $course->delete();

        return redirect()->route('tutor.courses.index')->with('status', 'course-deleted');
    }

    public function submit(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($request, $course);

        abort_unless($course->status === 'draft', 422);
        abort_if($course->lessons()->count() === 0, 422, 'Add at least one lesson before submitting.');

        $course->update(['status' => 'pending']);

        return redirect()->route('tutor.courses.index')->with('status', 'course-submitted');
    }

    private function authorizeOwner(Request $request, Course $course): void
    {
        abort_unless($course->tutor_id === $request->user()->id, 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'cover' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
