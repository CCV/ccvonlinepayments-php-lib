<?php

namespace CCVOnlinePayments\Lib\Enum;

enum PaymentFailureCode: string
{
    case EXPIRED = "expired";
    case CANCELLED = "cancelled";
    case UNSUFFICIENT_BALANCE = "unsufficient_balance";
    case FRAUD_DETECTED = "fraud_detected";
    case REJECTED = "rejected";
    case CARD_REFUSED = "card_refused";
    case INSUFFICIENT_FUNDS = "insufficient_funds";
    case PROCESSING_ERROR = "processing_error";
}
