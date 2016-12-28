<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class ICOTransactionsData {

    public static function getUserTransactions($userID) {
        $query = MySQL::getInstance()->prepare("SELECT fundingLevel, numberEQB, paidUSD, paidBTC, UNIX_TIMESTAMP(timeDate) AS timeDate FROM tokenSales WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}