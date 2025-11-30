<?php

namespace App\Validation\Constraints;

use Attribute;
use App\Validation\Validators\UniqueUserLoginAndPhoneValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class UniqueUserLoginAndPhone extends Constraint
{
    public string $message = 'User with login "{{ login }}" and phone "{{ phone }}" already exists.';

    /**
     * @return string|array|string[]
     */
    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return UniqueUserLoginAndPhoneValidator::class;
    }
}
