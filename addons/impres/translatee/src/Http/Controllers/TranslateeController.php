<?php

namespace Impres\Translatee\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Statamic\Facades\Addon;
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
        return view('translatee::index');
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
//        $addon = Addon::get('impres/translatee');
//        if ($addon->edition() !== 'pro') {
//            return back()->with('error', __('Please buy a license to use the import feature.'));
//        }

        try {
            Validator::make($request->all(), [
                'translation_file' => 'required|extensions:xlf,xliff|mimetypes:application/x-xliff+xml'
            ])->validate();
        } catch (\Exception $e) {
            return back()->with('error', __('Invalid file type. Please upload a .xlf or .xliff file.'));
        }

        try {
            $data = (new XliffParser(file_get_contents($request->translation_file)))->parse();
        } catch (\Exception $e) {
            return back()->with('error', __('Unable to read the uploaded file.'));
        }

        try {
            $importer->import($data);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to import the translations.');
        }

        return redirect(cp_route('translatee.index'))->with('success', __('Translations imported successfully!'));
    }
}
