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


    <div class="card p-4 content">
        <h3>{{ __('What actions should we take from here?') }}</h3>
        <div class="flex flex-wrap">
            <a href="{{ cp_route('translatee.processExport', $locale) }}" class="w-full lg:w-1/2 p-4 md:flex items-start hover:bg-gray-200 rounded-md group">
                <div class="h-8 w-8 mr-4 text-gray-800">
                    @cp_svg('icons/plump/file-zip')
                </div>
                <div class="text-blue flex-1 mb-4 md:mb-0 md:mr-6">
                    <h3>{{ __('Export content') }}</h3>
                    <p class="text-xs line-through">{{ __('On the next screen you can make a selection of content to export.') }}</p>
                    <p class="text-xs">{{ __('Click here and export it all at once!') }}</p>
                </div>
            </a>
            <a href="{{ cp_route('translatee.import', $locale) }}" class="w-full lg:w-1/2 p-4 md:flex items-start hover:bg-gray-200 rounded-md group">
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