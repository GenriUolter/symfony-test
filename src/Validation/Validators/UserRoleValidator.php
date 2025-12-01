<?php

namespace App\Validation\Validators;

use App\Entity\User;
use App\Enums\RoleEnum;
use App\Validation\Constraints\UserRole;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserRoleValidator extends ConstraintValidator
{
    /**
     * @param Security $security
     */
    public function __construct(
        private readonly Security $security,
    ) {
    }

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

            return;
        }

        /** @var User|null $user */
        $user = $this->security->getUser();

        if (!$user) {
            $this->context
                ->buildViolation('You must be authenticated to change role.')
                ->addViolation();

            return;
        }

        if ($user->getRole() !== RoleEnum::Root) {
            $this->context
                ->buildViolation('You cannot change role.')
                ->addViolation();
        }
    }
}
