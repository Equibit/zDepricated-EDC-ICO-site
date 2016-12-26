<?php

namespace PHP_REST_API\CronJobs;

use \PHP_REST_API\Data\NotificationsData;
use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Data\AuthUserData;

class SendNotifications {
    public function __construct() {
        $emailUserIDs = NotificationsData::getUnseenNotifications();

        forEach ($emailUserIDs AS $user) {
            $newUser = new AuthUserModel();
            $newUser->loadUser(mb_strtolower(AuthUserData::getUserNameByID($user['userID'])));
            if ($newUser->sendEmailNotification('Notification')) {
                NotificationsData::markedAsNotifid($user['userID']);
            }
        }
    }
}