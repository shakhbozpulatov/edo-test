<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case Draft = 'draft';
    case Signed = 'signed';
    case Deleted = 'deleted';

    public function label(): string
    {
        return match($this) {
            self::Draft   => 'Qoralama',
            self::Signed  => 'Imzolangan',
            self::Deleted => 'O\'chirilgan',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Draft   => 'badge-warning',
            self::Signed  => 'badge-success',
            self::Deleted => 'badge-danger',
        };
    }
}
