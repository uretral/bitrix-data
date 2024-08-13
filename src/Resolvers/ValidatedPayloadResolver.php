<?php

namespace Spatie\LaravelData\Resolvers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Contracts\ValidateableData;

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
