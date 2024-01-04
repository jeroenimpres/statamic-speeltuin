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

    public function exportFiles(Request $request)
    {
        $exportOptions = [
            'content' => 'everything'
        ];

        $exporter = new Exporter($exportOptions);

        return response()->download($exporter->run());
    }

    public function import()
    {
        return view('translatee::import');
    }

    public function processImport(Request $request, Importer $importer)
    {

//        $validatedData = $request->validate([
//            'document' => 'required|ends_with:.pdf',
//        ]);

        // The built in file validation doesn't work for some reason, so we have to manually
        // validate the file type.
        if (!in_array($request->translation_file->getClientOriginalExtension(), ['xlf', 'xliff'])) {
            return back()->withErrors(['file' => 'The file must be of the type .xlf or .xliff.']);
        }

        try {
            $data = (new XliffParser(file_get_contents($request->translation_file)))->parse();
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to read the file.');
        }

        try {
            $importer->import($data);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to import the translations.');
        }

        return redirect(cp_route('translatee.index'))->with('success', 'The file was imported!');
    }
}
