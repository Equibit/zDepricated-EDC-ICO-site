<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class AuthUserSuccessfulIPsData {
    
    public static function addSuccessfulIP($userID, $ipAddress) {
        $query = MySQL::getInstance()->prepare("INSERT INTO AuthUserSuccessfulIPs (userID, ipAddress, useCount, lastUsed) VALUES (:userID, :ipAddress, :useCount, FROM_UNIXTIME(:lastUsed)) ON DUPLICATE KEY UPDATE lastUsed=FROM_UNIXTIME(:lastUsed), useCount=useCount+1");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':ipAddress', $ipAddress);
        $query->bindValue(':useCount', 1);
        $query->bindValue(':lastUsed', time());
        return $query->execute();
    }

    public static function delAuthUserSuccessfulIPs($userID) {
        $query = MySQL::getInstance()->prepare("DELETE FROM AuthUserSuccessfulIPs WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        return $query->execute();
    }
    
}