<?php

namespace App\Enums;

enum PettyCashStatus: string
{
    case DRAFT = 'Draft';
    case PENDING_APPROVAL = 'Pending Approval';
    case POSTED = 'Posted';
    case REVERSED = 'Reversed';
    case DELETED = 'Deleted';

    public function label(): string
    {
        return $this->value;
    }
}