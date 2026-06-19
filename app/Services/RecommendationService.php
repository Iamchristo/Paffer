<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Heuristic, content-based + collaborative recommendations.
 * No external AI/LLM calls — purely derived from existing platform data
 * (industry/profile match, purchase/enrollment history, and the activity
 * of people a user follows).
 */
class RecommendationService
{
    public function people(User $user, int $limit = 5): Collection
    {
        $excludedIds = $user->following()->pluck('users.id')->push($user->id);
        $industry = $user->profile?->industry;

        $sameIndustry = collect();

        if ($industry) {
            $sameIndustry = User::with('profile')
                ->whereNotIn('id', $excludedIds)
                ->whereHas('profile', fn ($query) => $query->where('industry', $industry))
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }

        if ($sameIndustry->count() >= $limit) {
            return $sameIndustry;
        }

        $rest = User::whereNotIn('id', $excludedIds->merge($sameIndustry->pluck('id')))
            ->inRandomOrder()
            ->limit($limit - $sameIndustry->count())
            ->get();

        return $sameIndustry->concat($rest)->values();
    }

    public function products(User $user, int $limit = 6): Collection
    {
        $purchasedProductIds = $user->orders()
            ->with('items')
            ->get()
            ->flatMap(fn ($order) => $order->items->pluck('product_id'));

        $purchasedStoreIds = $user->orders()->pluck('store_id')->unique();
        $followingIds = $user->following()->pluck('users.id');

        $base = Product::query()->where('status', 'active')->whereNotIn('id', $purchasedProductIds);

        $repeatStore = $purchasedStoreIds->isNotEmpty()
            ? $base->clone()->whereIn('store_id', $purchasedStoreIds)->inRandomOrder()->limit($limit)->get()
            : collect();

        if ($repeatStore->count() >= $limit) {
            return $repeatStore;
        }

        $followedBought = $followingIds->isNotEmpty()
            ? $base->clone()
                ->whereNotIn('id', $repeatStore->pluck('id'))
                ->whereHas('orderItems.order', fn ($query) => $query->whereIn('buyer_id', $followingIds))
                ->inRandomOrder()
                ->limit($limit - $repeatStore->count())
                ->get()
            : collect();

        $combined = $repeatStore->concat($followedBought);

        if ($combined->count() >= $limit) {
            return $combined->values();
        }

        $fallback = $base->clone()
            ->whereNotIn('id', $combined->pluck('id'))
            ->latest()
            ->limit($limit - $combined->count())
            ->get();

        return $combined->concat($fallback)->values();
    }

    public function courses(User $user, int $limit = 6): Collection
    {
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        $enrolledTutorIds = Course::whereIn('id', $enrolledCourseIds)->pluck('tutor_id')->unique();
        $followingIds = $user->following()->pluck('users.id');

        $base = Course::query()->where('status', 'approved')->whereNotIn('id', $enrolledCourseIds);

        $sameTutor = $enrolledTutorIds->isNotEmpty()
            ? $base->clone()->whereIn('tutor_id', $enrolledTutorIds)->inRandomOrder()->limit($limit)->get()
            : collect();

        if ($sameTutor->count() >= $limit) {
            return $sameTutor;
        }

        $followedEnrolled = $followingIds->isNotEmpty()
            ? $base->clone()
                ->whereNotIn('id', $sameTutor->pluck('id'))
                ->whereHas('enrollments', fn ($query) => $query->whereIn('user_id', $followingIds))
                ->inRandomOrder()
                ->limit($limit - $sameTutor->count())
                ->get()
            : collect();

        $combined = $sameTutor->concat($followedEnrolled);

        if ($combined->count() >= $limit) {
            return $combined->values();
        }

        $fallback = $base->clone()
            ->whereNotIn('id', $combined->pluck('id'))
            ->latest()
            ->limit($limit - $combined->count())
            ->get();

        return $combined->concat($fallback)->values();
    }
}
