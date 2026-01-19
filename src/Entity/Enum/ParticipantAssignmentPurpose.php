<?php

namespace App\Entity\Enum;

enum ParticipantAssignmentPurpose: string
{
    case CONTRIBUTOR = 'contributor';
    case VALIDATOR = 'validator';
}
