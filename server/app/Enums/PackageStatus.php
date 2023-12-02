<?php

declare(strict_types=1);

namespace App\Enums;

enum PackageStatus: string
{
    case DRAFT = 'draft';
    case POSTED = 'posted';
    case IN_TRANSIT = 'in_transit';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case DELIVERED = 'delivered';
}
