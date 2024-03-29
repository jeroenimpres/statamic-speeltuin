<?php

namespace Impres\Translatee;

use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\CP\Nav;
use Statamic\Statamic;
use Impres\Translatee\Http\Controllers\TranslateeController;

class ServiceProvider extends AddonServiceProvider
{
    public function bootAddon()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'translatee');

        $this->addCpRoutes();

        Nav::extend(function ($nav) {
            $nav->tools('Translatee')
                ->route('translatee.index')
                ->icon('<svg viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="64px-Glyph" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="spe-translate-2" fill="#000000"><path d="M10.618,46 L15,37.236 L19.382,46 L10.618,46 Z M25.895,54.553 L15.895,34.553 C15.555,33.875 14.445,33.875 14.106,34.553 L4.106,54.553 C3.858,55.047 4.059,55.648 4.553,55.895 C5.046,56.142 5.647,55.942 5.895,55.447 L9.618,48 L20.382,48 L24.106,55.447 C24.281,55.798 24.634,56.001 25.001,56 C25.151,56 25.304,55.966 25.447,55.895 C25.941,55.648 26.142,55.047 25.895,54.553 L25.895,54.553 Z" id="Fill-85"></path><path d="M47,10 L43,10 C42.447,10 42,9.552 42,9 C42,8.448 42.447,8 43,8 L47,8 C47.553,8 48,8.448 48,9 C48,9.552 47.553,10 47,10" id="Fill-87"></path><path d="M57,12 L33,12 C32.447,12 32,12.448 32,13 C32,13.552 32.447,14 33,14 L49.938,14 C49.598,16.894 47.857,20.686 44.977,23.594 C42.961,21.569 41.484,19.088 40.674,16.687 C40.498,16.164 39.932,15.881 39.407,16.059 C38.884,16.235 38.602,16.803 38.779,17.326 C39.667,19.961 41.285,22.68 43.493,24.922 C41.185,26.757 38.324,28 35,28 C34.447,28 34,28.448 34,29 C34,29.552 34.447,30 35,30 C38.898,30 42.294,28.509 45.005,26.292 C47.712,28.506 51.097,30 55,30 C55.553,30 56,29.552 56,29 C56,28.448 55.553,28 55,28 C51.71,28 48.832,26.776 46.493,24.935 C49.674,21.717 51.629,17.514 51.949,14 L57,14 C57.553,14 58,13.552 58,13 C58,12.448 57.553,12 57,12" id="Fill-89"></path><path d="M49.707,38.293 L45.708,34.294 C45.615,34.201 45.505,34.128 45.382,34.077 C45.138,33.976 44.862,33.976 44.618,34.077 C44.495,34.128 44.385,34.201 44.292,34.294 L40.293,38.293 C39.902,38.684 39.902,39.316 40.293,39.707 C40.684,40.098 41.316,40.098 41.707,39.707 L44,37.414 L44,39 C44,45.065 39.065,50 33,50 C32.447,50 32,50.448 32,51 C32,51.552 32.447,52 33,52 C40.168,52 46,46.169 46,39 L46,37.414 L48.293,39.707 C48.488,39.902 48.744,40 49,40 C49.256,40 49.512,39.902 49.707,39.707 C50.098,39.316 50.098,38.684 49.707,38.293" id="Fill-91"></path><path d="M27,12 C19.832,12 14,17.831 14,25 L14,26.586 L11.707,24.293 C11.316,23.902 10.684,23.902 10.293,24.293 C9.902,24.684 9.902,25.316 10.293,25.707 L14.292,29.706 C14.385,29.799 14.495,29.872 14.618,29.923 C14.74,29.973 14.87,30 15,30 C15.13,30 15.26,29.973 15.382,29.923 C15.505,29.872 15.615,29.799 15.708,29.706 L19.707,25.707 C20.098,25.316 20.098,24.684 19.707,24.293 C19.316,23.902 18.684,23.902 18.293,24.293 L16,26.586 L16,25 C16,18.935 20.935,14 27,14 C27.553,14 28,13.552 28,13 C28,12.448 27.553,12 27,12" id="Fill-93"></path></g></g></svg>');
        });
    }

    private function addCpRoutes()
    {
        Statamic::pushCpRoutes(fn () => Route::name('translatee.')->prefix('translatee')->group(function () {
            Route::get('/', [TranslateeController::class, 'index'])->name('index');
            Route::get('/export', [TranslateeController::class, 'exportFiles'])->name('export');
            Route::get('/import', [TranslateeController::class, 'import'])->name('import');
            Route::post('/import', [TranslateeController::class, 'processImport'])->name('import');
        }));
    }
}
