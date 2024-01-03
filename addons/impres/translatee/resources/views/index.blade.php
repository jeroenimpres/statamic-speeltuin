@extends('statamic::layout')
@section('title', __('Translatee') )

@section('content')

    <header class="mb-6">
        <h1>{{ __('Translatee') }}</h1>
        <p>What do you want to say today?</p>
    </header>

    <h3 class="little-heading pl-0 mb-2">{{ __('Please choose a language') }}</h3>
    <p class="text-sm">
        {{ __('Please choose a language that you want to work with.') }}
        {!! sprintf(__('Your default language is %s, it uses the locale %s.'), "<strong>" . $defaultLocale['name'] . "</strong>", "<code>" . $defaultLocale['locale'] . "</code>") !!}
        {{ sprintf(__('As this will be the base for the other translations, you will not be able to export and import %s.'), $defaultLocale['name']) }}
    </p>
    <div class="card p-0 my-4">
        <table class="data-table">
            @foreach ($locales as $locale => $site)
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-4 h-4 mr-4">@cp_svg('icons/light/content-writing')</div>
                            <a href="{{ cp_route('translatee.options', $locale) }}">{{ $site['name'] }}</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection