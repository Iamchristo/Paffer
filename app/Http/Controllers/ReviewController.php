<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function storeForStore(Request $request, Store $store): RedirectResponse
    {
        $this->saveReview($request, $store);

        return back()->with('status', 'review-submitted');
    }

    public function storeForCourse(Request $request, Course $course): RedirectResponse
    {
        $this->saveReview($request, $course);

        return back()->with('status', 'review-submitted');
    }

    private function saveReview(Request $request, Store|Course $reviewable): void
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $reviewable->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated,
        );
    }
}
