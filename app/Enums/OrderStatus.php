<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case CREATED = 'created';
    case SUCCESS = 'success';
    case FAIL = 'fail';
    case NOT_ANSWERED = 'not_answered';
    case NEXT_TIME = 'next_time';

    public function getLabel(): string
    {
        return match ($this) {
            self::CREATED => __('shop/order.customer_order_status.created'),
            self::SUCCESS => __('shop/order.customer_order_status.success'),
            self::FAIL => __('shop/order.customer_order_status.fail'),
            self::NOT_ANSWERED => __('shop/order.customer_order_status.not_answered'),
            self::NEXT_TIME => __('shop/order.customer_order_status.next_time'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::CREATED => 'success',
            self::SUCCESS => 'success',
            self::FAIL => 'danger',
            self::NOT_ANSWERED => 'warning',
            self::NEXT_TIME => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CREATED => 'heroicon-m-arrow-path',
            self::SUCCESS => 'heroicon-m-arrow-path',
            self::FAIL => 'heroicon-m-check-badge',
            self::NOT_ANSWERED => 'heroicon-m-x-circle',
            self::NEXT_TIME => 'heroicon-m-sparkles',
        };
    }
}
