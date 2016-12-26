<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\NotificationsData;

class NotificationsModel {
    private $userID;

    public function __construct($userName) {
        $this->userID = AuthUserData::getUserIDByUserName($userName);
    }
    
    public function insertNotification($title, $text) {
        return NotificationsData::insertNotification($this->userID, $title, $text);
    }
    
    public function getNotifications() {
        $toReturn = NotificationsData::getUserNotifications($this->userID);

        foreach($toReturn as $key => $item) {
            if ($item['seen'] == 1) $toReturn[$key]['seen'] = true;
            else $toReturn[$key]['seen'] = false;
        }

        return $toReturn;
    }
    
    public function getUnseenNotifications() {
        $toReturn = NotificationsData::getUnseenUserNotifications($this->userID);

        foreach($toReturn as $key => $item) {
            if ($item['seen'] == 1) $toReturn[$key]['seen'] = true;
            else $toReturn[$key]['seen'] = false;
        }

        return $toReturn;
    }

    public function readNotification($notificationID) {
        return NotificationsData::updateNotificationRead($this->userID, $notificationID);
    }

    public function deleteNotification($notificationID) {
        return NotificationsData::delNotification($this->userID, $notificationID);
    }
}