<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class AvailablePaymentMethodsData {
    
    public static function hasCreditCard() {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(*) AS count FROM AvailablePaymentMethods WHERE paymentMethodName=:paymentMethodName AND paymentMethodAvailable=1");
        $query->bindValue(':paymentMethodName', 'creditCard');
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] >= 1);
    }

    public static function hasBitPay() {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(*) AS count FROM AvailablePaymentMethods WHERE paymentMethodName=:paymentMethodName AND paymentMethodAvailable=1");
        $query->bindValue(':paymentMethodName', 'bitPay');
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] >= 1);
    }

    public static function turnOffBitPay() {
        $query = MySQL::getInstance()->prepare("UPDATE AvailablePaymentMethods SET paymentMethodAvailable=0 WHERE paymentMethodName=:paymentMethodName");
        $query->bindValue(':paymentMethodName', 'bitPay');
        return $query->execute();
    }

    public static function getAvailablePaymentMethods() {
        $query = MySQL::getInstance()->prepare("SELECT paymentMethodID, paymentMethodName FROM AvailablePaymentMethods WHERE paymentMethodAvailable=1");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
}