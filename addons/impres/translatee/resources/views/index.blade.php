@extends('statamic::layout')
@section('title', __('Translatee') )

@section('content')

    <header class="mb-6">
        <h1>{{ __('Translatee') }}</h1>
        <p>What do you want to say today?</p>
    </header>

    <div class="card p-4 content">
        <h3>{{ __('Please choose one of two actions.') }}</h3>
        <div class="flex flex-wrap">
            <a href="{{ cp_route('translatee.export') }}" class="w-full lg:w-1/2 p-4 md:flex items-start hover:bg-gray-200 rounded-md group">
                <div class="h-8 w-8 mr-4 text-gray-800">
                    @cp_svg('icons/plump/file-zip')
                </div>
                <div class="text-blue flex-1 mb-4 md:mb-0 md:mr-6">
                    <h3>{{ __('Export') }}</h3>
                    <p>{{ __('Export .xliff files for all available languages in this installation. It will contain collections, terms and globals.') }}</p>
                    <p class="text-xs">{{ __('Please and thank you.') }}</p>
                </div>
            </a>
            <a href="{{ cp_route('translatee.import') }}" class="w-full lg:w-1/2 p-4 md:flex items-start hover:bg-gray-200 rounded-md group">
                <div class="h-8 w-8 mr-4 text-gray-800">
                    @cp_svg('icons/plump/text-formatting-shadow-text')
                </div>
                <div class="text-blue flex-1 mb-4 md:mb-0 md:mr-6">
                    <h3>{{ __('Import XLIFF file') }}</h3>
                    <p class="text-xs">{{ __('Import the XLIFF file that contains your translated content') }}</p>
                </div>
            </a>
        </div>
    </div>
@endsection