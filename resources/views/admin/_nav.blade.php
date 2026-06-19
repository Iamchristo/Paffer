<div class="flex flex-wrap gap-4 text-sm font-medium border-b border-gray-200 pb-3">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Dashboard') }}</a>
    <a href="{{ route('admin.verification.index') }}" class="{{ request()->routeIs('admin.verification.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Verification') }}</a>
    <a href="{{ route('admin.moderation.index') }}" class="{{ request()->routeIs('admin.moderation.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Moderation') }}</a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Users') }}</a>
    <a href="{{ route('admin.wallets.index') }}" class="{{ request()->routeIs('admin.wallets.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Wallets') }}</a>
    <a href="{{ route('admin.workspaces.index') }}" class="{{ request()->routeIs('admin.workspaces.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Workspaces') }}</a>
    <a href="{{ route('admin.recommendations.edit') }}" class="{{ request()->routeIs('admin.recommendations.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Recommendations') }}</a>
    <a href="{{ route('admin.mobile.edit') }}" class="{{ request()->routeIs('admin.mobile.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Mobile App') }}</a>
    <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Announcements') }}</a>
    <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __('Settings') }}</a>
</div>
