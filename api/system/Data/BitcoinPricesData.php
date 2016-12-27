<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class BitcoinPricesData {

    public static function getPriceFromSource($priceSource) {
        $query = MySQL::getInstance()->prepare("SELECT lastPrice, UNIX_TIMESTAMP(lastUpdate) AS lastUpdate FROM BitcoinPrices WHERE priceSource=:priceSource");
        $query->bindValue(':priceSource', $priceSource);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function updatePriceForSource($lastPrice, $priceSource) {
        $query = MySQL::getInstance()->prepare("INSERT INTO BitcoinPrices (priceSource, lastPrice) VALUES (:priceSource, :lastPrice) ON DUPLICATE KEY UPDATE lastPrice=:lastPrice");
        $query->bindValue(':lastPrice', $lastPrice);
        $query->bindValue(':priceSource', $priceSource);
        return $query->execute();
    }

}