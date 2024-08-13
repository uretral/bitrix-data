<?php

namespace Uretral\BitrixData\Support\Validation;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationRuleParser;
use Uretral\BitrixData\Attributes\Validation\Accepted;
use Uretral\BitrixData\Attributes\Validation\AcceptedIf;
use Uretral\BitrixData\Attributes\Validation\ActiveUrl;
use Uretral\BitrixData\Attributes\Validation\After;
use Uretral\BitrixData\Attributes\Validation\AfterOrEqual;
use Uretral\BitrixData\Attributes\Validation\Alpha;
use Uretral\BitrixData\Attributes\Validation\AlphaDash;
use Uretral\BitrixData\Attributes\Validation\AlphaNumeric;
use Uretral\BitrixData\Attributes\Validation\ArrayType;
use Uretral\BitrixData\Attributes\Validation\Bail;
use Uretral\BitrixData\Attributes\Validation\Before;
use Uretral\BitrixData\Attributes\Validation\BeforeOrEqual;
use Uretral\BitrixData\Attributes\Validation\Between;
use Uretral\BitrixData\Attributes\Validation\BooleanType;
use Uretral\BitrixData\Attributes\Validation\Confirmed;
use Uretral\BitrixData\Attributes\Validation\CurrentPassword;
use Uretral\BitrixData\Attributes\Validation\Date;
use Uretral\BitrixData\Attributes\Validation\DateEquals;
use Uretral\BitrixData\Attributes\Validation\DateFormat;
use Uretral\BitrixData\Attributes\Validation\Declined;
use Uretral\BitrixData\Attributes\Validation\DeclinedIf;
use Uretral\BitrixData\Attributes\Validation\Different;
use Uretral\BitrixData\Attributes\Validation\Digits;
use Uretral\BitrixData\Attributes\Validation\DigitsBetween;
use Uretral\BitrixData\Attributes\Validation\Dimensions;
use Uretral\BitrixData\Attributes\Validation\Distinct;
use Uretral\BitrixData\Attributes\Validation\DoesntEndWith;
use Uretral\BitrixData\Attributes\Validation\DoesntStartWith;
use Uretral\BitrixData\Attributes\Validation\Email;
use Uretral\BitrixData\Attributes\Validation\EndsWith;
use Uretral\BitrixData\Attributes\Validation\Enum;
use Uretral\BitrixData\Attributes\Validation\ExcludeIf;
use Uretral\BitrixData\Attributes\Validation\ExcludeUnless;
use Uretral\BitrixData\Attributes\Validation\ExcludeWith;
use Uretral\BitrixData\Attributes\Validation\ExcludeWithout;
use Uretral\BitrixData\Attributes\Validation\Exists;
use Uretral\BitrixData\Attributes\Validation\File;
use Uretral\BitrixData\Attributes\Validation\Filled;
use Uretral\BitrixData\Attributes\Validation\GreaterThan;
use Uretral\BitrixData\Attributes\Validation\GreaterThanOrEqualTo;
use Uretral\BitrixData\Attributes\Validation\Image;
use Uretral\BitrixData\Attributes\Validation\In;
use Uretral\BitrixData\Attributes\Validation\InArray;
use Uretral\BitrixData\Attributes\Validation\IntegerType;
use Uretral\BitrixData\Attributes\Validation\IP;
use Uretral\BitrixData\Attributes\Validation\IPv4;
use Uretral\BitrixData\Attributes\Validation\IPv6;
use Uretral\BitrixData\Attributes\Validation\Json;
use Uretral\BitrixData\Attributes\Validation\LessThan;
use Uretral\BitrixData\Attributes\Validation\LessThanOrEqualTo;
use Uretral\BitrixData\Attributes\Validation\ListType;
use Uretral\BitrixData\Attributes\Validation\Lowercase;
use Uretral\BitrixData\Attributes\Validation\MacAddress;
use Uretral\BitrixData\Attributes\Validation\Max;
use Uretral\BitrixData\Attributes\Validation\MaxDigits;
use Uretral\BitrixData\Attributes\Validation\Mimes;
use Uretral\BitrixData\Attributes\Validation\MimeTypes;
use Uretral\BitrixData\Attributes\Validation\Min;
use Uretral\BitrixData\Attributes\Validation\MinDigits;
use Uretral\BitrixData\Attributes\Validation\MultipleOf;
use Uretral\BitrixData\Attributes\Validation\NotIn;
use Uretral\BitrixData\Attributes\Validation\NotRegex;
use Uretral\BitrixData\Attributes\Validation\Nullable;
use Uretral\BitrixData\Attributes\Validation\Numeric;
use Uretral\BitrixData\Attributes\Validation\Password;
use Uretral\BitrixData\Attributes\Validation\Present;
use Uretral\BitrixData\Attributes\Validation\Prohibited;
use Uretral\BitrixData\Attributes\Validation\ProhibitedIf;
use Uretral\BitrixData\Attributes\Validation\ProhibitedUnless;
use Uretral\BitrixData\Attributes\Validation\Prohibits;
use Uretral\BitrixData\Attributes\Validation\Regex;
use Uretral\BitrixData\Attributes\Validation\Required;
use Uretral\BitrixData\Attributes\Validation\RequiredArrayKeys;
use Uretral\BitrixData\Attributes\Validation\RequiredIf;
use Uretral\BitrixData\Attributes\Validation\RequiredUnless;
use Uretral\BitrixData\Attributes\Validation\RequiredWith;
use Uretral\BitrixData\Attributes\Validation\RequiredWithAll;
use Uretral\BitrixData\Attributes\Validation\RequiredWithout;
use Uretral\BitrixData\Attributes\Validation\RequiredWithoutAll;
use Uretral\BitrixData\Attributes\Validation\Same;
use Uretral\BitrixData\Attributes\Validation\Size;
use Uretral\BitrixData\Attributes\Validation\Sometimes;
use Uretral\BitrixData\Attributes\Validation\StartsWith;
use Uretral\BitrixData\Attributes\Validation\StringType;
use Uretral\BitrixData\Attributes\Validation\Timezone;
use Uretral\BitrixData\Attributes\Validation\Ulid;
use Uretral\BitrixData\Attributes\Validation\Unique;
use Uretral\BitrixData\Attributes\Validation\Uppercase;
use Uretral\BitrixData\Attributes\Validation\Url;
use Uretral\BitrixData\Attributes\Validation\Uuid;
use Uretral\BitrixData\Exceptions\CouldNotCreateValidationRule;

class ValidationRuleFactory
{
    public function create(string $rule): ValidationRule
    {
        [$keyword, $parameters] = ValidationRuleParser::parse($rule);

        /** @var \Uretral\BitrixData\Attributes\Validation\StringValidationAttribute|null $ruleClass */
        $ruleClass = $this->mapping()[Str::snake($keyword)] ?? null;

        if ($ruleClass === null) {
            throw CouldNotCreateValidationRule::create($rule);
        }

        return $ruleClass::create(...$parameters);
    }

    protected function mapping(): array
    {
        return [
            Accepted::keyword() => Accepted::class,
            AcceptedIf::keyword() => AcceptedIf::class,
            ActiveUrl::keyword() => ActiveUrl::class,
            After::keyword() => After::class,
            AfterOrEqual::keyword() => AfterOrEqual::class,
            Alpha::keyword() => Alpha::class,
            AlphaDash::keyword() => AlphaDash::class,
            AlphaNumeric::keyword() => AlphaNumeric::class,
            ArrayType::keyword() => ArrayType::class,
            Bail::keyword() => Bail::class,
            Before::keyword() => Before::class,
            BeforeOrEqual::keyword() => BeforeOrEqual::class,
            Between::keyword() => Between::class,
            BooleanType::keyword() => BooleanType::class,
            Confirmed::keyword() => Confirmed::class,
            CurrentPassword::keyword() => CurrentPassword::class,
            Date::keyword() => Date::class,
            DateEquals::keyword() => DateEquals::class,
            DateFormat::keyword() => DateFormat::class,
            Declined::keyword() => Declined::class,
            DeclinedIf::keyword() => DeclinedIf::class,
            Different::keyword() => Different::class,
            Digits::keyword() => Digits::class,
            DigitsBetween::keyword() => DigitsBetween::class,
            Dimensions::keyword() => Dimensions::class,
            Distinct::keyword() => Distinct::class,
            Email::keyword() => Email::class,
            DoesntEndWith::keyword() => DoesntEndWith::class,
            DoesntStartWith::keyword() => DoesntStartWith::class,
            EndsWith::keyword() => EndsWith::class,
            Enum::keyword() => Enum::class,
            ExcludeIf::keyword() => ExcludeIf::class,
            ExcludeUnless::keyword() => ExcludeUnless::class,
            ExcludeWith::keyword() => ExcludeWith::class,
            ExcludeWithout::keyword() => ExcludeWithout::class,
            Exists::keyword() => Exists::class,
            File::keyword() => File::class,
            Filled::keyword() => Filled::class,
            GreaterThan::keyword() => GreaterThan::class,
            GreaterThanOrEqualTo::keyword() => GreaterThanOrEqualTo::class,
            Image::keyword() => Image::class,
            In::keyword() => In::class,
            InArray::keyword() => InArray::class,
            IntegerType::keyword() => IntegerType::class,
            IP::keyword() => IP::class,
            IPv4::keyword() => IPv4::class,
            IPv6::keyword() => IPv6::class,
            Json::keyword() => Json::class,
            LessThan::keyword() => LessThan::class,
            LessThanOrEqualTo::keyword() => LessThanOrEqualTo::class,
            ListType::keyword() => ListType::class,
            Lowercase::keyword() => Lowercase::class,
            MacAddress::keyword() => MacAddress::class,
            Max::keyword() => Max::class,
            MaxDigits::keyword() => MaxDigits::class,
            Mimes::keyword() => Mimes::class,
            MimeTypes::keyword() => MimeTypes::class,
            Min::keyword() => Min::class,
            MinDigits::keyword() => MinDigits::class,
            MultipleOf::keyword() => MultipleOf::class,
            NotIn::keyword() => NotIn::class,
            NotRegex::keyword() => NotRegex::class,
            Nullable::keyword() => Nullable::class,
            Numeric::keyword() => Numeric::class,
            Password::keyword() => Password::class,
            Present::keyword() => Present::class,
            Prohibited::keyword() => Prohibited::class,
            ProhibitedIf::keyword() => ProhibitedIf::class,
            ProhibitedUnless::keyword() => ProhibitedUnless::class,
            Prohibits::keyword() => Prohibits::class,
            Regex::keyword() => Regex::class,
            Required::keyword() => Required::class,
            RequiredArrayKeys::keyword() => RequiredArrayKeys::class,
            RequiredIf::keyword() => RequiredIf::class,
            RequiredUnless::keyword() => RequiredUnless::class,
            RequiredWith::keyword() => RequiredWith::class,
            RequiredWithAll::keyword() => RequiredWithAll::class,
            RequiredWithout::keyword() => RequiredWithout::class,
            RequiredWithoutAll::keyword() => RequiredWithoutAll::class,
            Same::keyword() => Same::class,
            Size::keyword() => Size::class,
            Sometimes::keyword() => Sometimes::class,
            StartsWith::keyword() => StartsWith::class,
            StringType::keyword() => StringType::class,
            Timezone::keyword() => Timezone::class,
            Unique::keyword() => Unique::class,
            Uppercase::keyword() => Uppercase::class,
            Url::keyword() => Url::class,
            Ulid::keyword() => Ulid::class,
            Uuid::keyword() => Uuid::class,
        ];
    }
}
