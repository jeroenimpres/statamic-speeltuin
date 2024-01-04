@extends('statamic::layout')
@section('title', __('Import translations') )

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
    </header>

    <div class="card p-4 content">
        <h3>{{ __('Select the .xliff or .xlf file that you want to import') }}</h3>
        <form method="post" action="{{ cp_route('translatee.import') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="flex items-center">
                <input type="file" name="translation_file">
                <input type="submit" class="btn btn-primary" value="{{ __('Upload and import') }}">
            </div>
        </form>
    </div>
@endsection