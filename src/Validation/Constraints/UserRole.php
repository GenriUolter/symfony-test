<?php

namespace App\Validation\Constraints;

use App\Validation\Validators\UserRoleValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class UserRole extends Constraint
{
    public string $message = 'Invalid user role provided.';

    /**
     * @return string|array|string[]
     */
    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return UserRoleValidator::class;
    }
}
