<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class BitcoinPricesData {

    public static function getPriceFromSource($priceSource) {
        $query = MySQL::getInstance()->prepare("SELECT lastPrice, lastUpdate FROM BitcoinChats WHERE priceSource=:priceSource");
        $query->bindValue(':priceSource', $priceSource);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}