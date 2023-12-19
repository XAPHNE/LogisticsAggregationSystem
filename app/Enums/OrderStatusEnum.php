<?php
namespace App\Enums;

enum OrderStatusEnum : string
{
    case OPEN = 'Open';
    case ACCEPTED = 'Accepted';
    case TRANSIT = 'Transit';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
}
