<?php

namespace App\Entity\Enum;

enum ActivitySubjectType: string
{
    case PROJECT = 'project';
    case CAPTURE = 'capture';
    case CAPTURE_ELEMENT = 'capture_element';
}
