<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum GenderEnums: string implements HasColor, HasIcon, HasLabel
{
    case MALE = 'male';
    case FEMALE = 'female';

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => __('shop/customer.gender_options.male'),
            self::FEMALE => __('shop/customer.gender_options.female'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::MALE => 'success',
            self::FEMALE => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::MALE => 'heroicon-m-arrow-trending-up',
            self::FEMALE => 'heroicon-m-arrow-trending-down',
        };
    }
}
