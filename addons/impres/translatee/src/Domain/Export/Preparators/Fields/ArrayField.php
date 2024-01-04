<?php

namespace Impres\Translatee\Domain\Export\Preparators\Fields;

use Illuminate\Support\Str;

class ArrayField extends Field
{
    /**
     * Parse and add the current field to the list of fields.
     *
     * @param array $data
     * @return array
     */
    public function map($data, $nonTranslatableFields = [])
    {

        foreach ($data['original_value'] as $index => $string) {
            $this->fields[$data['field_name'].'.'.$index] = [
                'type' => $data['field_type'],
                'name' => $data['field_name'].'.'.$index.':'.$data['field_type'],
                'original' => $string,
                'translate' => in_array(Str::afterLast($index, '.'), $nonTranslatableFields, true) ? 'no' : 'yes',
                'localized' => $data['localized_value'][$index] ?? '',
            ];
        }

        return $this->fields;
    }
}
