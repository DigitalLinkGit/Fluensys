<?php

namespace App\Entity\Enum;

enum ActivityActorType: string
{
    case USER = 'user';
    case CONTACT = 'contact';
    case SYSTEM = 'system';
}
