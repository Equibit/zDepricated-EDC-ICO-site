<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class ICOTransactionsData {

    public static function getTotalEQBSold() {
        $query = MySQL::getInstance()->prepare("SELECT SUM(numberEQB) AS numberEQB FROM tokenSales WHERE rejected=0");
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['numberEQB'];
    }

    public static function getUserTransactions($userID) {
        $query = MySQL::getInstance()->prepare("SELECT fundingLevel, numberEQB, paidUSD, paidBTC, UNIX_TIMESTAMP(timeDate) AS timeDate, completed FROM tokenSales WHERE userID=:userID AND rejected=0");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllTransactions() {
        $query = MySQL::getInstance()->prepare("SELECT userID, fundingLevel, numberEQB, paidUSD, paidBTC, UNIX_TIMESTAMP(timeDate) AS timeDate, completed, rejected FROM tokenSales");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}