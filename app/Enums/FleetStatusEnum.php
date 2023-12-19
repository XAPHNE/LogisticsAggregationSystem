<?php
namespace App\Enums;

enum FleetStatusEnum : string
{
    case AVAILABLE = 'Available';
    case ASSIGNED = 'Assigned';
}
