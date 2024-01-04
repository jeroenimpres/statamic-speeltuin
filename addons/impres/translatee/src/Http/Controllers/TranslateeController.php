<?php

namespace Impres\Translatee\Http\Controllers;


use Illuminate\Http\Request;
use Statamic\Facades\Config;
use Statamic\Facades\Collection;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Taxonomy;
use Statamic\Http\Controllers\Controller;
use Impres\Translatee\Actions\GetAdditionalSiteAction;
use Impres\Translatee\Actions\GetDefaultSiteAction;
use Impres\Translatee\Domain\Export\Exporter;
use Impres\Translatee\Domain\Import\Importer;
use Impres\Translatee\Domain\Import\Parsers\XliffParser;


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

    public function import(Request $request, string $lang)
    {
        $params = [
            'title' => sprintf(__('Import translated file for %s'), Config::getLocaleName($lang)),
            'locale' => $lang,
        ];

        return view('translatee::import', $params);
    }

    public function processImport(Request $request, string $lang, Importer $importer)
    {

        $params = [
            'title' => sprintf(__('Import translated file for %s'), Config::getLocaleName($lang)),
            'locale' => $lang,
        ];

        // The built in file validation doesn't work for some reason, so we have to manually
        // validate the file type.
        if (!in_array($request->file->getClientOriginalExtension(), ['xlf', 'xliff'])) {
            return back()->withErrors(['file' => 'The file must be of the type .xlf or .xliff.']);
        }

        try {
            $data = (new XliffParser(file_get_contents($request->file)))->parse();
        } catch (\Exception $e) {
            return $this->errorResponse($e, 'Unable to read the file.');
        }

        try {
            $importer->import($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e, 'Unable to import the translations.');
        }

        return back()->with('success', 'The file was imported!');

        return view('translatee::options', $params);
    }
}
