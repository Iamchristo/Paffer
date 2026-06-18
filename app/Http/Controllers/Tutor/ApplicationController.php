<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->is_tutor) {
            return redirect()->route('tutor.courses.index');
        }

        return view('tutor.apply');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user()->is_tutor, 403);

        $request->validate([
            'expertise' => ['required', 'string', 'max:2000'],
        ]);

        $request->user()->update([
            'is_tutor' => true,
            'tutor_status' => 'pending',
        ]);

        return redirect()->route('tutor.courses.index')->with('status', 'application-submitted');
    }
}
