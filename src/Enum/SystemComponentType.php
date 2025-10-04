<?php

namespace App\Enum;

enum SystemComponentType: string
{
    case APPLICATION = 'application';
    case SERVICE = 'service';
    case DATABASE = 'database';
    case SERVER = 'server';
    case ETL = 'etl';
    case SAAS = 'saas';
    case OTHER = 'other';
}
