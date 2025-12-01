<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Root = 'root';
    case User = 'user';

    /**
     * @param string $roleKey
     * @return self
     */
    public static function getRoleByKey(string $roleKey): self
    {
        return match ($roleKey) {
            self::Root->value => self::Root,
            self::User->value => self::User,
            default           => null
        };
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
