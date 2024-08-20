<?php

namespace App\Filament\Clusters;

use App\Models\Product;
use Filament\Clusters\Cluster;

class Products extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $cluster = Product::class;
}
