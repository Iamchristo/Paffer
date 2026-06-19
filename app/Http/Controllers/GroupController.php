<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        $groups = Group::query()
            ->withCount('members')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('groups.index', [
            'groups' => $groups,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $group = $request->user()->ownedGroups()->create([
            ...$validated,
            'slug' => Str::slug($validated['name']).'-'.Str::random(6),
        ]);

        $group->members()->attach($request->user());

        return redirect()->route('groups.show', $group)->with('status', 'group-created');
    }

    public function show(Request $request, Group $group): View
    {
        $group->load('owner');

        $posts = $group->posts()->with(['user', 'comments.user'])->paginate(10);

        return view('groups.show', [
            'group' => $group,
            'posts' => $posts,
            'membersCount' => $group->members()->count(),
            'isMember' => $request->user()?->groups()->where('groups.id', $group->id)->exists() ?? false,
        ]);
    }
}
