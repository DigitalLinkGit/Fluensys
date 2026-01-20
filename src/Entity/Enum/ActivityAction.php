<?php

namespace App\Entity\Enum;

enum ActivityAction: string
{
    case CREATED = 'created';
    case TEMPLATE_CREATED = 'template_created';
    case UPDATED = 'updated';
    case TEMPLATE_UPDATED = 'template_updated';
    case DELETED = 'deleted';
    case TEMPLATE_DELETED = 'template_deleted';
    case PUBLISHED = 'published';
    case UNPUBLISHED = 'unpublished';
    case SUBMITTED = 'submitted';
    case VALIDATED = 'validated';
    case STATUS_CHANGED = 'status_changed';
}
