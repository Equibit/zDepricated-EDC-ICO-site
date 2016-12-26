<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class ManageAPIKeysData {

    public static function getManageAPIKeysData($userID) {
        $query = MySQL::getInstance()->prepare("SELECT id, keyDesc, keyPublic, keySecret FROM AuthUserKeys WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserIDByPublicKey($keyPublic) {
        $query = MySQL::getInstance()->prepare("SELECT userID FROM AuthUserKeys WHERE keyPublic=:keyPublic");
        $query->bindValue(':keyPublic', $keyPublic);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['userID'];
    }

    public static function getManageAPIKeyData($userID, $keyID) {
        $query = MySQL::getInstance()->prepare("SELECT id, keyDesc, keyPublic, keySecret FROM AuthUserKeys WHERE userID=:userID AND id=:id");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':id', $keyID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSecretByPublicKey($userID, $keyPublic) {
        $query = MySQL::getInstance()->prepare("SELECT keySecret FROM AuthUserKeys WHERE keyPublic=:keyPublic AND userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':keyPublic', $keyPublic);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['keySecret'];
    }

    public static function updateAPIKey($userID, $id, $keyDesc) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUserKeys SET keyDesc=:keyDesc WHERE userID=:userID AND id=:id");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':id', $id);
        $query->bindValue(':keyDesc', $keyDesc);
        return $query->execute();
    }

    public static function insertAPIKey($userID, $keyDesc, $keyPublic, $keySecret) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO AuthUserKeys (userID, keyDesc, keyPublic, keySecret) VALUES (:userID, :keyDesc, :keyPublic, :keySecret)");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':keyDesc', $keyDesc);
        $query->bindValue(':keyPublic', $keyPublic);
        $query->bindValue(':keySecret', $keySecret);
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function delAPIKey($userID, $id) {
        $query = MySQL::getInstance()->prepare("DELETE FROM AuthUserKeys WHERE userID=:userID AND id=:id");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':id', $id);
        return $query->execute();
    }
}