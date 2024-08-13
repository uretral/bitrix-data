<?php

namespace Uretral\BitrixData\Resolvers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Contracts\ValidateableData;
use Uretral\BitrixData\Support\Validation\DataRules;
use Uretral\BitrixData\Support\Validation\ValidationPath;

class DataValidatorResolver
{
    public function __construct(
        protected DataValidationRulesResolver $dataValidationRulesResolver,
        protected DataValidationMessagesAndAttributesResolver $dataValidationMessagesAndAttributesResolver
    ) {
    }

    /** @param class-string<ValidateableData&BaseData> $dataClass */
    public function execute(
        string $dataClass,
        Arrayable|array $payload,
    ): Validator {
        $payload = $payload instanceof Arrayable ? $payload->toArray() : $payload;

        $rules = $this->dataValidationRulesResolver->execute(
            $dataClass,
            $payload,
            ValidationPath::create(),
            DataRules::create()
        );

        ['messages' => $messages, 'attributes' => $attributes] = $this->dataValidationMessagesAndAttributesResolver->execute(
            $dataClass,
            $payload,
            ValidationPath::create()
        );

        $validator = ValidatorFacade::make(
            $payload,
            $rules,
            $messages,
            $attributes
        );

        if (method_exists($dataClass, 'stopOnFirstFailure')) {
            $validator->stopOnFirstFailure( Container::getInstance()->make()->call([$dataClass, 'stopOnFirstFailure']));
        }

        $dataClass::withValidator($validator);

        return $validator;
    }
}
