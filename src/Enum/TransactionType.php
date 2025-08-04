<?php
namespace CCVOnlinePayments\Lib\Enum;

enum TransactionType: string {
    case SALE = "sale";
    case CREDIT = "credit";
    case AUTHORIZE = "authorise";
}
