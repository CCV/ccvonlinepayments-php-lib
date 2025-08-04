<?php

namespace CCVOnlinePayments\Lib\Enum;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case FAILED = 'failed';
    case MANUAL_INTERVENTION = 'manualintervention';
    case SUCCESS = 'success';
}
