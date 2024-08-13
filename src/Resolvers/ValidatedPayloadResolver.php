<?php

namespace Uretral\BitrixData\Resolvers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Uretral\BitrixData\Contracts\BaseData;
use Uretral\BitrixData\Contracts\ValidateableData;

class ValidatedPayloadResolver
{
    /** @param class-string<ValidateableData&BaseData> $dataClass */
    public function execute(
        string $dataClass,
        Validator $validator
    ): array {
        try {
            $validator->validate();
        } catch (ValidationException $exception) {
            if (method_exists($dataClass, 'redirect')) {
                $exception->redirectTo( Container::getInstance()->make()->call([$dataClass, 'redirect']));
            }

            if (method_exists($dataClass, 'redirectRoute')) {
                $exception->redirectTo(route( Container::getInstance()->make()->call([$dataClass, 'redirectRoute'])));
            }

            if (method_exists($dataClass, 'errorBag')) {
                $exception->errorBag( Container::getInstance()->make()->call([$dataClass, 'errorBag']));
            }

            throw $exception;
        }

        return $validator->validated();
    }
}
