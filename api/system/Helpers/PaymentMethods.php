<?php
namespace PHP_REST_API\Helpers;

/*
 * Enums for two factor
 */
abstract class PaymentMethods extends BasicEnum {
    const CreditCard = 1;
    const PayPal = 2;
    const BitPay = 3;
}
