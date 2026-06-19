<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Recommendations') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin._nav')

            @if (session('status') === 'recommendations-updated')
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Recommendation settings updated.') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['users_with_industry'] }}</p>
                    <p class="text-xs text-gray-500">{{ __('Profiles with an industry set') }}</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_products'] }}</p>
                    <p class="text-xs text-gray-500">{{ __('Active products') }}</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_courses'] }}</p>
                    <p class="text-xs text-gray-500">{{ __('Approved courses') }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-2">{{ __('Recommendation engine') }}</h3>
                <p class="text-xs text-gray-500 mb-4">{{ __('A heuristic, content-based + collaborative engine built from existing platform data (industry match, purchase/enrollment history, and the activity of people a member follows). No external AI/LLM calls or API keys are involved.') }}</p>

                <form method="POST" action="{{ route('admin.recommendations.update') }}" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="recommend_on_feed" name="recommend_on_feed" value="1" @checked(old('recommend_on_feed', $values['recommend_on_feed'])) class="rounded border-gray-300">
                        <x-input-label for="recommend_on_feed" :value="__('Show \'People you may know\' on the network feed')" />
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="recommend_on_marketplace" name="recommend_on_marketplace" value="1" @checked(old('recommend_on_marketplace', $values['recommend_on_marketplace'])) class="rounded border-gray-300">
                        <x-input-label for="recommend_on_marketplace" :value="__('Show \'Recommended for you\' products on the marketplace')" />
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="recommend_on_learn" name="recommend_on_learn" value="1" @checked(old('recommend_on_learn', $values['recommend_on_learn'])) class="rounded border-gray-300">
                        <x-input-label for="recommend_on_learn" :value="__('Show \'Recommended for you\' courses on the learn catalog')" />
                    </div>

                    <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
