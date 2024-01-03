<?php

namespace Impres\Translatee\Domain\Export;

use Statamic\Addons\TranslationManager\Helpers\Config;
use Impres\Translatee\Domain\Export\Exporters\Xliff;
use Impres\Translatee\Domain\Export\Collectors\DataCollector;
use Impres\Translatee\Domain\Export\Preparators\DataPreparator;

class Exporter
{
    private array $config;
    private DataCollector $dataCollector;
    private DataPreparator $dataPreparator;

    public function __construct($options)
    {
        $this->config = [
            'export_path' => __DIR__ .'/exports/',
        ];
        $this->dataCollector = new DataCollector($this->config, $options);
        $this->dataPreparator = new DataPreparator($options);
    }

    public function run()
    {
        // Clear out the result directory.
        $this->clearExportsDirectory();

        $data = $this->dataCollector->collect($this->config);
        $data = $this->dataPreparator->prepare($data);

        $files = [];
        foreach ($data as $locale => $data) {
            $files[] = (new Xliff($this->config))->create($locale, $data);
        }

        if (count($files) > 1) {
            return FileZipper::zip($files, $this->config['export_path']);
        }

        return $files[0];
    }

    protected function parseConfig($config)
    {
        if (is_string($config['exclude_page_ids'])) {
            $config['exclude_page_ids'] = explode(',', $config['exclude_page_ids']);
        }

        if (is_string($config['exclude_collection_slugs'])) {
            $config['exclude_collection_slugs'] = explode(',', $config['exclude_collection_slugs']);
        }

        return $config;
    }

    /**
     * Removes all files from the export directory to make
     * room for the new files.
     *
     * @return void
     */
    protected function clearExportsDirectory()
    {
        $exportPath = Config::get('export_path');
        $files = scandir($exportPath);
        $filesToKeep = ['.', '..', '.DS_Store', '.gitkeep'];

        foreach ($files as $file) {
            if (!in_array($file, $filesToKeep)) {
                unlink($exportPath . $file);
            }
        }
    }
}
