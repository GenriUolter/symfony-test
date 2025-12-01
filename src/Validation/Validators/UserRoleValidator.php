<?php

namespace App\Validation\Validators;

use App\Enums\RoleEnum;
use App\Validation\Constraints\UserRole;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserRoleValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UserRole) {
            return;
        }

        if (!$value instanceof RoleEnum) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
