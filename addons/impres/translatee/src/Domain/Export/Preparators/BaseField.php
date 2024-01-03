<?php


namespace Impres\Translatee\Domain\Export\Preparators;

use Statamic\Support\Arr;
use Statamic\Facades\Fieldset;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Entry;
use Statamic\Facades\Taxonomy;
//use Illuminate\Support\Arr;

class BaseField
{
    /**
     * The field.
     *
     * @var array
     */
    protected $field;

    /**
     * Retrieve the field information.
     *
     * @param object $item
     * @param string $field
     */
    public function __construct($item, $field)
    {
        $this->field = $this->getField($item, $field);
    }

    public function getType()
    {
        return $this->field->get('type');
    }

    /**
     * When accessing the string version of the class, return the field type.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->field['type'];
    }

    /**
     * Returns an attribute value on the field.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->field[$key] ?? null;
    }

    /**
     * Determines whether the field is valid and localizable.
     *
     * @return bool
     */
    public function shouldBeTranslated()
    {
        return $this->field && !empty($this->field->get('type')) && !empty($this->field->get('localizable'));
    }

    /**
     * Retrieves the field information.
     *
     * @param object $item
     * @param string $field
     * @return array|null
     */
    protected function getFieldV2($item, $field)
    {
        if (in_array(class_basename($item->original), ['Entry'])) {
            try {
                $fieldset = Fieldset::find($item->original->collection()->get('fieldset'))->contents();
            } catch (\Exception $e) {
                $fieldset = Fieldset::find($item->original->get('fieldset'))->contents();
            }
        } elseif (in_array(class_basename($item->original), ['Term', 'LocalizedTerm'])) {
            try {
                $fieldsetName = $item->original->taxonomy()->get('blueprints');
                dd($item->original->taxonomy()->blueprint(), $fieldsetName);

                if (!$fieldsetName) {
                    if (is_string($item->get($field))) {
                        return ['type' => 'text', 'localizable' => true];
                    }
                }

                $fieldset = Fieldset::find($fieldsetName)->contents();
            } catch (\Exception $e) {
                if (is_string($item->get($field))) {
                    return ['type' => 'text', 'localizable' => true];
                }
            }
        } else {
            try {
                $fieldset = Fieldset::find($item->original->get('fieldset'))->contents();
            } catch (\Exception $e) {
                if (!method_exists($item->original, 'collection')) {
                    return;
                }

                $fieldset = Fieldset::find($item->original->collection()->get('fieldset'))->contents();
            }
        }

        // Arrays are formatted as field.index. We only want the field name.
        $field = explode('.', $field)[0];

        if (isset($fieldset['sections'])) {
            $fieldset['fields'] = collect($fieldset['sections'])->flatMap(function ($section) {
                return $section['fields'] ?? [];
            })->toArray();
        }

        // Merge 'partial' fieldtypes into fields array
        $fieldset['fields'] = collect($fieldset['fields'])->flatMap(function ($field, $key) {
            if (Arr::get($field, 'type') === 'partial') {
                return Fieldset::find($field['fieldset'])->contents()['fields'] ?? [];
            }

            return [$key => $field];
        })->toArray();

        return $fieldset['fields'][$field] ?? null;
    }


    /**
     * Retrieves the field information for Statamic 3.
     *
     * @param object $item
     * @param string $field
     * @return array|null
     */
    protected function getField($item, $field)
    {
        $blueprint = null;

        // Bepaal de klasse van het item en haal de bijbehorende blueprint op.
        switch (class_basename($item->original)) {
            case 'Entry':
                $blueprint = Entry::find($item->id())->blueprint();
                break;
            case 'Term':
            case 'LocalizedTerm':
                $blueprint = Taxonomy::findByHandle($item->taxonomyHandle())->termBlueprint($item->slug());
                break;
            // Voeg indien nodig extra cases toe voor andere content types.
        }

        // Als er geen blueprint is gevonden, retourneer null.
        if (!$blueprint) {
            return null;
        }

        // Haal de velden op uit de blueprint.
        $fields = $blueprint->fields()->all();

        // Ondersteuning voor geneste velden in array-formaat.
        $fieldKey = explode('.', $field)[0];
        return $fields[$fieldKey] ?? null;
    }

}
