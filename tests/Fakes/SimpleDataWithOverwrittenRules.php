<?php

namespace Uretral\BitrixData\Tests\Fakes;

class SimpleDataWithOverwrittenRules extends SimpleData
{
    public static function rules(): array
    {
        return [
            'string' => ['string', 'required', 'min:10', 'max:100'],
        ];
    }
}
