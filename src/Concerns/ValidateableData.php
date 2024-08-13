<?php

namespace Uretral\BitrixData\Concerns;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\Validator;
use Uretral\BitrixData\Resolvers\DataValidationRulesResolver;
use Uretral\BitrixData\Support\DataContainer;
use Uretral\BitrixData\Support\Validation\DataRules;
use Uretral\BitrixData\Support\Validation\ValidationContext;
use Uretral\BitrixData\Support\Validation\ValidationPath;

/**
 * @method static array rules(ValidationContext $context)
 * @method static array messages(...$args)
 * @method static array attributes(...$args)
 * @method static bool stopOnFirstFailure()
 * @method static string redirect()
 * @method static string redirectRoute()
 * @method static string errorBag()
 */
trait ValidateableData
{
    public static function validate(Arrayable|array $payload): Arrayable|array
    {
        $validator = DataContainer::get()->dataValidatorResolver()->execute(
            static::class,
            $payload,
        );

        return DataContainer::get()->validatedPayloadResolver()->execute(
            static::class,
            $validator,
        );
    }

    public static function validateAndCreate(Arrayable|array $payload): static
    {
        return static::factory()->alwaysValidate()->from($payload);
    }

    public static function withValidator(Validator $validator): void
    {
        return;
    }

    public static function getValidationRules(array $payload): array
    {
        return  Container::getInstance()->make(DataValidationRulesResolver::class)->execute(
            static::class,
            $payload,
            ValidationPath::create(),
            DataRules::create(),
        );
    }
}
