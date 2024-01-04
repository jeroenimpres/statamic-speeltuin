<?php

namespace Impres\Translatee\Domain\Export\Preparators;

use Impres\Translatee\Domain\Export\Preparators\Fields\ArrayField;
use Impres\Translatee\Domain\Export\Preparators\Fields\ReplicatorField;
use Impres\Translatee\Domain\Export\Preparators\Fields\StringField;
use Statamic\Facades\Config;

class FieldPreparator
{
    /**
     * The processed fields, mapped into an exportable structure.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Prepares the fields in the page or entry to be exported.
     *
     * @param object $item
     * @return array
     */
    public function prepare($item, $nonTranslatableFields = [])
    {
        // Reset the fields for each item.
        $this->fields = [];

        // Items from a global set are Variables, which do not have a locale directly
        if(class_basename($item->original) === 'Variables') {
            $localizedItem = $item;
        } else {
            $localizedItem = $item->in(Config::getDefaultLocale());
        }

        $data = $localizedItem->data();

        if ($slug = @$localizedItem->slug()) {
            $data['slug'] = $slug;
        }

        foreach ($data as $fieldName => $value) {
            if(!$fieldName) {
                continue;
            }
            $field = new BaseField($item, $fieldName);

            // Determine whether the field should be translated, or skipped.
            if (!$fieldName || !$field->shouldBeTranslated()) {
                continue;
            }

            // Handle the various field types. They all store the actual
            // values in different ways, so we have to map them into a
            // common structure before exporting them.
            $this->handleFieldTypes($field, [
                'original_value' => $value,
                'localized_value' => $item->get($fieldName) ?: '',
                'field_name' => $fieldName,
                'field_type' => $field->getType(),
            ], $nonTranslatableFields);
        }

        return $this->fields;
    }

    /**
     * Parses the various field types into a common structure.
     *
     * @param Field $field
     * @param array $fieldData
     * @return void
     */
    protected function handleFieldTypes($field, $fieldData, $nonTranslatableFields = [])
    {
        $fieldType = $field->getType();

        switch ($fieldType) {
            case 'array':
            case 'collection':
            case 'list':
            case 'tags':
            case 'checkbox':
                $this->fields = (new ArrayField($this->fields))->map($fieldData, $nonTranslatableFields);
                break;

            case 'table':
            case 'replicator':
                $this->fields = (new ReplicatorField($this->fields))->map($fieldData, $nonTranslatableFields);
                break;

            // "Default" fields include:
            // - Bard
            // - Regular string values
            default:
                $this->fields = (new StringField($this->fields))->map($fieldData, $nonTranslatableFields);
                break;
        }
    }
}
