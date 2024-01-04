<?php

namespace Impres\Translatee\Domain\Export\Preparators;

use Statamic\Facades\Config;
use Statamic\Facades\URL;

class DataPreparator
{
    /**
     * The field preparator class.
     *
     * @var FieldPreparator
     */
    protected $fieldPreparator;

    /**
     * The available translation languages.
     * Each language will generate 1 file.
     *
     * @var array
     */
    protected $locales;

    // TODO: This pro'ly isn't the best place
    protected array $nonTranslatableFields = [
        'id',
        'enabled',
        'type',
        'suggest',
        'radio',
        'checkboxes',
        'level',
        'start',
        'replicator'
    ];

    public function __construct()
    {
        $this->fieldPreparator = new FieldPreparator;
        $this->locales = collect(Config::getLocales())->flatMap(function ($locale) {
            return [$locale => []];
        });
    }

    /**
     * Organize the collected data into a structure that we can export.
     *
     * @param Collection $data
     * @return Collection
     */
    public function prepare($data)
    {
        return $this->splitIntoLocales($data)->map(function ($items, $locale) {

            foreach ($items as $index => $item) {
                // An item can be a Global, Term or Collection
                if(class_basename($item->original) !== 'GlobalSet') {
                    $items[$index] = $this->prepareEntryOrTermItems($item, $locale);
                } else {
                    $items[$index] = $this->prepareEntryOrTermItems($item, $locale);
                }
            }

            return $items;
        });
    }

    private function prepareEntryOrTermItems($item, $locale)
    {
        return [
            'meta' => [
                'id' => $item->id(),
                'type' => class_basename($item->original),
                'source-language' => Config::getDefaultLocale(),
                'target-language' => $locale,
                'uri' => $item->locale().$item->original->uri(),
                'url' => URL::prependSiteUrl($item->original->uri(), $item->locale()),
            ],
            'fields' => $this->fieldPreparator->prepare($item, $this->nonTranslatableFields),
        ];
    }

    /**
     * Splits the data into chunks organized by locale.
     *
     * @param Collection $data
     * @return Collection
     */
    protected function splitIntoLocales($data)
    {
        // TODO: Deze functie werkt niet voor Globalsets (origin() is geen functie) en mogelijk ook niet voor Terms
        return $data->map(function($page) {
            $page->original = $page->origin() ?: $page;
            return $page;
        })->groupBy('locale');
    }
}
