@extends('statamic::layout')
@section('title', __('Translatee') )

@section('content')

    <header class="mb-6">
        <div class="flex">
            <a href="{{ cp_route('translatee.index') }}" class="flex-initial flex p-2 -m-2 items-center text-xs text-gray-700 hover:text-gray-900">
                <div class="h-6 rotate-180 svg-icon using-svg">
                    @cp_svg('icons/micro/chevron-right')
                </div>
                <span>{{ __('Translatee') }}</span>
            </a>
        </div>
        <h1>{{ $title }}</h1>
    </header>

    <h3 class="little-heading pl-0 mb-2">{{ __('Please choose what you want to export') }}</h3>

    <div class="card p-0 my-4">
        <table class="data-table">
            <tr><th colspan="2"><div class="flex items-center py-1 px-3">
                {{ __('Collections') }}
            </div></th></tr>
            @foreach ($collections as $collection)

                <tr>
                    <th class="checkbox-column w-4"><input type="checkbox" name="collections[]" value="{{ $collection }}" ></th>
                    <td><div class="flex items-center space-1">
                    {{ $collection['title'] }}
                </div></td></tr>
            @endforeach
        </table>
    </div>
@endsection