<?php

namespace App\Enum;

enum ParticipantAssignmentPurpose: string
{
    case CONTRIBUTOR = 'contributor';
    case VALIDATOR = 'validator';
}
