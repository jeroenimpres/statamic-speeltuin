<?php

namespace Impres\Translatee\Domain\Export\Preparators\Fields;

use Illuminate\Support\Str;

class StringField extends Field
{
    /**
     * Parse and add the current field to the list of fields.
     *
     * @param array $data
     * @return array
     */
    public function map($data, $nonTranslatableFields = [])
    {
        $this->fields[$data['field_name']] = [
            'type' => $data['field_type'],
            'name' => $data['field_name'].':'.$data['field_type'],
            'translate' => in_array(Str::afterLast($data['field_name'], '.'), $nonTranslatableFields, true) ? 'no' : 'yes',
            'original' => $data['original_value'],
            'localized' => $data['localized_value'],
        ];

        return $this->fields;
    }
}
