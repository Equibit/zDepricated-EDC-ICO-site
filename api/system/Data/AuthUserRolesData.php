<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class AuthUserRolesData {

    public static function addUserRole($userID, $roleID) {
        $query = MySQL::getInstance()->prepare("INSERT INTO AuthUserRoles (userID, roleID) VALUES (:userID, :roleID) ON DUPLICATE KEY UPDATE roleID=:roleID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':roleID', $roleID);
        return $query->execute();
    }

    public static function isRole($roleName) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(roleName) AS count FROM AvailableRoles WHERE roleName=:roleName");
        $query->bindValue(':roleName', $roleName);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] == 1);
    }

    public static function getAvailableRoles() {
        $query = MySQL::getInstance()->prepare("SELECT roleName, roleDesc FROM AvailableRoles WHERE isAvailable=1");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserRoles($userID) {
        $query = MySQL::getInstance()->prepare("SELECT roleName FROM AuthUserRoles LEFT JOIN AvailableRoles ON AuthUserRoles.roleID=AvailableRoles.roleID  WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRoleID($roleName) {
        $query = MySQL::getInstance()->prepare("SELECT roleID FROM AvailableRoles WHERE roleName=:roleName");
        $query->bindValue(':roleName', $roleName);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['roleID'];
    }

    public static function delAuthUserRoles($userID) {
        $query = MySQL::getInstance()->prepare("DELETE FROM AuthUserRoles WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        return $query->execute();
    }

}