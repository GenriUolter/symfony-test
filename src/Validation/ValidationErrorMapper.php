<?php

namespace App\Validation;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorMapper
{
    /**
     * @param ConstraintViolationListInterface $violations
     * @return void
     * @throws AppException
     */
    public function throwIfNotValid(ConstraintViolationListInterface $violations): void
    {
        if (count($violations) === 0) {
            return;
        }

        $errors = [];

        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        throw new AppException(
            'Validation error',
            $errors,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
