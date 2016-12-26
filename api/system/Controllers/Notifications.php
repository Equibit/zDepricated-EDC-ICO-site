<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Models\NotificationsModel;

class Notifications extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            $notification = new NotificationsModel($headers['Auth-User']);

            echo json_encode(StatusReturn::S200($notification->getNotifications()), JSON_NUMERIC_CHECK);
        }
    }
    function post_xhr($notificationID) {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            $notification = new NotificationsModel($headers['Auth-User']);

            $notification->readNotification($notificationID);

            echo json_encode(StatusReturn::S200(Array('id' => $notificationID)));
        }
    }
    function delete_xhr($notificationID) {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            $notification = new NotificationsModel($headers['Auth-User']);

            $notification->deleteNotification($notificationID);

            echo json_encode(StatusReturn::S200(Array('id' => $notificationID)));
        }
    }
}