<?php

namespace App\Validation\Validators;

use App\Repository\UserRepository;
use App\Validation\Constraints\UniqueUserLoginAndPhone;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserLoginAndPhoneValidator extends ConstraintValidator
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        private  readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueUserLoginAndPhone) {
            return;
        }

        if ($value->login === null || $value->phone === null) {
            return;
        }

        if ($this->userWithLoginAndPhoneExists($value->login, $value->phone)) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('login')
                ->setParameter('{{ login }}', $value->login)
                ->setParameter('{{ phone }}', $value->phone)
                ->addViolation();
        }
    }

    /**
     * @param string $login
     * @param string $phone
     * @return bool
     */
    private function userWithLoginAndPhoneExists(string $login, string $phone): bool
    {
        $count = $this->userRepository
            ->count([
                'login' => $login,
                'phone' => $phone,
            ]);

        return $count > 0;
    }
}
