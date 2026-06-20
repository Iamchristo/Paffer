@php($siteTheme = \App\Models\Setting::get('site_theme', 'default'))
@if ($siteTheme === 'paffar')
    <link rel="stylesheet" href="{{ asset('css/themes/paffar.css') }}">
@elseif ($siteTheme === 'custom')
    @php($customCss = \App\Models\Setting::get('theme_custom_css', ''))
    @if (filled($customCss))
        <style id="custom-theme-css">{!! $customCss !!}</style>
    @endif
@endif
