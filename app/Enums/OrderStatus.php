<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';
    case Old = 'old';

    // case Processing = 'processing';

    // case Shipped = 'shipped';

    // case Delivered = 'delivered';

    // case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => __('shop/order.customer_order_status.new'),
            self::Old => __('shop/order.customer_order_status.old'),
            // self::Processing => 'Processing',
            // self::Shipped => 'Shipped',
            // self::Delivered => 'Delivered',
            // self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::New => 'success',
            self::Old => 'warning',
            // self::Processing => 'warning',
            // self::Shipped, self::Delivered => 'success',
            // self::Cancelled => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::New => 'heroicon-m-sparkles',
            self::Old => 'heroicon-m-users',
            // self::Processing => 'heroicon-m-arrow-path',
            // self::Shipped => 'heroicon-m-truck',
            // self::Delivered => 'heroicon-m-check-badge',
            // self::Cancelled => 'heroicon-m-x-circle',
        };
    }
}
