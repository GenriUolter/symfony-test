<?php

namespace App\Resources;

use App\Entity\User;

readonly class UserAuthResource
{
    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    /**
     * @param User $user
     * @return self
     */
    public static function fromEntity(User $user): self
    {
        return new self($user);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'   => $this->user->getId(),
            'role' => $this->user->getRole()->value,
        ];
    }
}
