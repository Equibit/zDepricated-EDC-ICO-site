<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class NotificationsData {

    public static function insertNotification($userID, $title, $text) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO Notifications (userID, title, text, dateTime) VALUES (:userID, :title, :text, FROM_UNIXTIME(:dateTime))");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':title', $title);
        $query->bindValue(':text', $text);
        $query->bindValue(':date', time());
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function getUserNotifications($userID) {
        $query = MySQL::getInstance()->prepare("SELECT id, title, text, seen, UNIX_TIMESTAMP(dateTime) as dateTime FROM Notifications WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUnseenUserNotifications($userID) {
        $query = MySQL::getInstance()->prepare("SELECT id, title, text, seen, UNIX_TIMESTAMP(dateTime) as dateTime FROM Notifications WHERE userID=:userID AND seen=0 AND notified=0");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateNotificationRead($userID, $id) {
        $query = MySQL::getInstance()->prepare("UPDATE Notifications SET seen=1 WHERE userID=:userID AND id=:id");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':id', $id);
        return $query->execute();
    }

    public static function delNotification($userID, $id) {
        $query = MySQL::getInstance()->prepare("DELETE FROM Notifications WHERE userID=:userID AND id=:id");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':id', $id);
        return $query->execute();
    }

    public static function getUnseenNotifications() {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(*) AS count, userID FROM Notifications WHERE notified=0 AND seen=0 GROUP BY userID");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function markedAsNotifid($userID) {
        $query = MySQL::getInstance()->prepare("UPDATE Notifications SET notified=1 WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        return $query->execute();
    }
}