<?php
namespace CCVOnlinePayments\Lib\Enum;

enum OrderLineType: string
{
    case PHYSICAL = "PHYSICAL";
    case DISCOUNT = "DISCOUNT";
    case DIGITAL = "DIGITAL";
    case SHIPPING_FEE = "SHIPPING_FEE";
    case STORE_CREDIT = "STORE_CREDIT";
    case GIFT_CARD = "GIFT_CARD";
    case SURCHARGE = "SURCHARGE";
    case SALES_TAX = "SALES_TAX";
    case DEPOSIT = "DEPOSIT";
}
