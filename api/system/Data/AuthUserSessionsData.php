<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class AuthUserSessionsData {

    public static function addNewSession($userID, $sessionSecret, $sessionIP, $sessionUserAgent) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO AuthUserSessions (userID, sessionSecret, sessionIP, sessionUserAgent, sessionLastActive) VALUES (:userID, :sessionSecret, :sessionIP, :sessionUserAgent, FROM_UNIXTIME(:sessionLastActive)) ON DUPLICATE KEY UPDATE sessionSecret=:sessionSecret, sessionIP=:sessionIP, sessionUserAgent=:sessionUserAgent, sessionLastActive=FROM_UNIXTIME(:sessionLastActive)");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':sessionSecret', $sessionSecret);
        $query->bindValue(':sessionIP', $sessionIP);
        $query->bindValue(':sessionUserAgent', $sessionUserAgent);
        $query->bindValue(':sessionLastActive', time());
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function updateSessionActivity($sessionID) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUserSessions SET sessionLastActive=FROM_UNIXTIME(:sessionLastActive) WHERE sessionID=:sessionID");
        $query->bindValue(':sessionLastActive', time());
        $query->bindValue(':sessionID', $sessionID);
        return $query->execute();
    }

    public static function getUserSessions($userID) {
        $query = MySQL::getInstance()->prepare("SELECT * FROM AuthUserSessions WHERE userID=:userID ORDER BY sessionID DESC");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function delExpiredSessions($userID) {
        $query = MySQL::getInstance()->prepare("DELETE FROM AuthUserSessions WHERE userID=:userID AND NOW() >= DATE_ADD(sessionLastActive, INTERVAL :seconds SECOND)");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':seconds', _SESSION_EXPIRE_SECONDS_);
        return $query->execute();
    }

    public static function delAuthUserSessions($userID) {
        $query = MySQL::getInstance()->prepare("DELETE FROM AuthUserSessions WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        return $query->execute();
    }
}