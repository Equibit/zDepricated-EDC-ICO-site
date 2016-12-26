<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\AdminNotifyData;

class AdminNotify extends BaseAPIController {
    function get_xhr($userID) {
        if ($this->checkAuth()) {
            echo json_encode(StatusReturn::S200(AdminNotifyData::getUserNotifications($userID)), JSON_NUMERIC_CHECK);
        }
    }
    function post_xhr($userID, $notificationID = null) {
        if ($this->checkAuth()) {
            if (!empty($_POST['title']) && !empty($_POST['text'])) {
                if (is_null($notificationID)) {
                    $notificationID = AdminNotifyData::insertNotification($userID, $_POST['title'], $_POST['text']);
                } else {
                    AdminNotifyData::updateNotification($userID, $notificationID, $_POST['title'], $_POST['text']);
                }
                echo json_encode(StatusReturn::S200(Array('id' => $notificationID)));
            } else {
                echo json_encode(StatusReturn::E400('Missing Required Information!'));
            }
        }
    }
    function delete_xhr($userID, $notificationID) {
        if ($this->checkAuth()) {
            AdminNotifyData::delNotification($userID, $notificationID);
            echo json_encode(StatusReturn::S200(Array('id' => $notificationID)));
        }
    }
}