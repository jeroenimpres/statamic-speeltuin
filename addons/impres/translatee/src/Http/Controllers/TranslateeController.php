<?php

namespace Impres\Translatee\Http\Controllers;


use Illuminate\Http\Request;
use Impres\Translatee\Domain\Export\Exporter;
use Statamic\Facades\Config;
use Statamic\Facades\Collection;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Taxonomy;
use Statamic\Http\Controllers\Controller;
use Impres\Translatee\Actions\GetAdditionalSiteAction;
use Impres\Translatee\Actions\GetDefaultSiteAction;


class TranslateeController extends Controller
{
    public function index()
    {
        return view('translatee::index', [
            'defaultLocale' => app(GetDefaultSiteAction::class)->execute(),
            'locales' => app(GetAdditionalSiteAction::class)->execute(),
        ]);
    }


    public function options(Request $request, string $lang)
    {
        return view('translatee::options', [
            'title' => Config::getLocaleName($lang),
            'locale' => $lang
        ]);
    }

    public function export(Request $request, string $lang)
    {
        $params = [
            'title' => sprintf(__('Export content to translate to %s'), Config::getLocaleName($lang)),
            'locale' => $lang,
            'collections' => Collection::all(),
            'taxonomies' => Taxonomy::all(),
            'globals' => GlobalSet::all()
        ];

        return view('translatee::export', $params);
    }

    public function processExport(Request $request, string $lang)
    {
        $params = [
            'title' => Config::getLocaleName($lang),
            'locale' => $lang,
            'collections' => Collection::all(),
            'taxonomies' => Taxonomy::all(),
            'globals' => GlobalSet::all()
        ];

        $exporter = new Exporter(['content' => 'everything']);

        return response()->download($exporter->run());

        return view('translatee::options', $params);
    }
}
