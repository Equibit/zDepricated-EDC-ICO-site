<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class AdminNotifyData {

    public static function insertNotification($userID, $title, $text) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO Notifications (userID, title, text, dateTime) VALUES (:userID, :title, :text, FROM_UNIXTIME(:dateTime))");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':title', $title);
        $query->bindValue(':text', $text);
        $query->bindValue(':dateTime', time());
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function updateNotification($userID, $id, $title, $text) {
        $query = MySQL::getInstance()->prepare("UPDATE Notifications SET title=:title, text=:text WHERE userID=:userID AND id=:id");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':id', $id);
        $query->bindValue(':title', $title);
        $query->bindValue(':text', $text);
        return $query->execute();
    }

    public static function getUserNotifications($userID) {
        $query = MySQL::getInstance()->prepare("SELECT id, title, text, seen, UNIX_TIMESTAMP(dateTime) as dateTime FROM Notifications WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delNotification($userID, $id) {
        $query = MySQL::getInstance()->prepare("DELETE FROM Notifications WHERE userID=:userID AND id=:id");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':id', $id);
        return $query->execute();
    }
}