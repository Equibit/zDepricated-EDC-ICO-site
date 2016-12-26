<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class AvailableFactorsData {
    
    public static function getAvailableFactors() {
        $query = MySQL::getInstance()->prepare("SELECT factorID, factorType, factorDesc, available FROM AvailableFactors");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
}