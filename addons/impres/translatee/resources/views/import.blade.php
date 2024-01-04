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
        <form method="post" action="{{ cp_route('translatee.import', $locale) }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group select-fieldtype width-100 ">
                <div class="field-inner">
                    <label class="block">File</label>

                    <small class="help-block">
                        <p>Must be a .xlf or .xliff file.</p>
                    </small>

                    <input type="file" name="file">
                </div>
            </div>

            <input type="submit" class="btn btn-primary" value="Import" style="margin-top:20px;">
        </form>
    </div>
@endsection