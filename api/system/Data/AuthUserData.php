<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class AuthUserData {

    public static function userExistConfirmed($authUser) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(userName) AS count FROM AuthUser WHERE userName=:userName" . ( _EMAIL_CONFIRMATION_ || _PHONE_CONFIRMATION_ ?  " AND (emailConfirmed=1 OR phoneConfirmed=1)" : "" ));
        $query->bindValue(':userName', $authUser);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] == 1);
    }

    public static function emailExist($email) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(email) AS count FROM AuthUser WHERE email=:email");
        $query->bindValue(':email', $email);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] == 1);
    }

    public static function phoneExist($phone) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(phone) AS count FROM AuthUser WHERE phone=:phone");
        $query->bindValue(':phone', $phone);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] == 1);
    }

    public static function userExist($authUser) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(userName) AS count FROM AuthUser WHERE userName=:userName");
        $query->bindValue(':userName', $authUser);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] == 1);
    }

    public static function userExistByID($userID) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(userName) AS count FROM AuthUser WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] == 1);
    }

    public static function userNameExists($authUser) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(userName) AS count FROM AuthUser WHERE userName=:userName");
        $query->bindValue(':userName', $authUser);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] == 1);
    }

    public static function resetFailedLogin($userID) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET failedLoginCount=:failedLoginCount, failedLoginTime=:failedLoginTime WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':failedLoginCount', null);
        $query->bindValue(':failedLoginTime', null);
        return $query->execute();
    }

    public static function clearExtraKey($userID) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET extraKey=:param, extraKeyData=:param, extraKeyType=:param, extraKeyCreated=:param WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':param', null);
        return $query->execute();
    }

    public static function confirmEmail($userID) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET emailConfirmed=:param WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':param', 1);
        return $query->execute();
    }

    public static function confirmPhone($userID) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET phoneConfirmed=:param WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':param', 1);
        return $query->execute();
    }

    /*
     *      Setters
     */
    public static function addNewUser($userName, $email, $phone, $password, $salt, $securityQuestion, $securityAnswer) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO AuthUser (userName, email, phone, password, salt, securityQuestion, securityAnswer, accountCreated) VALUES (:userName, :email, :phone, :password, :salt, :securityQuestion, :securityAnswer, FROM_UNIXTIME(:accountCreated))");
        $query->bindValue(':userName', $userName);
        $query->bindValue(':email', $email);
        $query->bindValue(':phone', $phone);
        $query->bindValue(':password', $password);
        $query->bindValue(':salt', $salt);
        $query->bindValue(':securityQuestion', $securityQuestion);
        $query->bindValue(':securityAnswer', $securityAnswer);
        $query->bindValue(':accountCreated', time());
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function addSubNewUser($userName, $email, $parentUserID) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO AuthUser (userName, email, password, salt, securityQuestion, securityAnswer, accountCreated, parentUserID) VALUES (:userName, :email, :password, :salt, :securityQuestion, :securityAnswer, FROM_UNIXTIME(:accountCreated), :parentUserID)");
        $query->bindValue(':userName', $userName);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', '');
        $query->bindValue(':salt', '');
        $query->bindValue(':securityQuestion', '');
        $query->bindValue(':securityAnswer', '');
        $query->bindValue(':parentUserID', $parentUserID);
        $query->bindValue(':accountCreated', time());
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    /*
     *      Updaters
     */
    public static function updatePasswordAndSalt($userID, $password, $salt) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET password=:password, salt=:salt WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':password', $password);
        $query->bindValue(':salt', $salt);
        return $query->execute();
    }

    public static function updateEmail($userID, $email) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET email=:email WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':email', $email);
        return $query->execute();
    }

    public static function updatePhone($userID, $phone) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET phone=:phone WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':phone', $phone);
        return $query->execute();
    }

    public static function updateFactor($userID, $twoFactorType) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET twoFactorType=:twoFactorType WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':twoFactorType', $twoFactorType);
        return $query->execute();
    }

    public static function updateNotifications($userID, $emailNotifications, $phoneNotifications) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET emailNotifications=:emailNotifications, phoneNotifications=:phoneNotifications WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':emailNotifications', $emailNotifications);
        $query->bindValue(':phoneNotifications', $phoneNotifications);
        return $query->execute();
    }

    public static function updateLanguage($userID, $lang) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET baseLang=:baseLang WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':baseLang', $lang);
        return $query->execute();
    }

    public static function updateExtraKey($userID, $extraKey, $extraKeyType, $extraKeyData = null) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET extraKey=:extraKey, extraKeyType=:extraKeyType, extraKeyData=:extraKeyData, extraKeyCreated=FROM_UNIXTIME(:extraKeyCreated) WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':extraKey', $extraKey);
        $query->bindValue(':extraKeyData', $extraKeyData);
        $query->bindValue(':extraKeyType', $extraKeyType);
        $query->bindValue(':extraKeyCreated', time());
        return $query->execute();
    }

    public static function updateSecurityQuestion($userID, $securityQuestion, $securityAnswer) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET securityQuestion=:securityQuestion, securityAnswer=:securityAnswer WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':securityQuestion', $securityQuestion);
        $query->bindValue(':securityAnswer', $securityAnswer);
        return $query->execute();
    }

    public static function updateFailedLogin($userID, $failedLoginCount, $failedLoginTime) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET failedLoginCount=:failedLoginCount, failedLoginTime=FROM_UNIXTIME(:failedLoginTime) WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':failedLoginCount', $failedLoginCount);
        $query->bindValue(':failedLoginTime', $failedLoginTime);
        return $query->execute();
    }

    /*
     *      Getters
     */
    public static function getUserData($authUser) {
        $query = MySQL::getInstance()->prepare("SELECT userID, userName, email, phone, password, salt, emailConfirmed, phoneConfirmed, twoFactorType, emailNotifications, phoneNotifications, securityQuestion, securityAnswer, failedLoginCount, UNIX_TIMESTAMP(failedLoginTime) AS failedLoginTime, extraKey, extraKeyData, extraKeyType, baseLang, UNIX_TIMESTAMP(extraKeyCreated) AS extraKeyCreated, UNIX_TIMESTAMP(accountCreated) AS accountCreated, parentUserID FROM AuthUser WHERE userName=:userName ORDER BY userID");
        $query->bindValue(':userName', $authUser);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUsersByParent($parentUserID) {
        $query = MySQL::getInstance()->prepare("SELECT userID AS id, userName, email, phone, twoFactorType FROM AuthUser WHERE parentUserID=:parentUserID");
        $query->bindValue(':parentUserID', $parentUserID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserNameByEmail($email) {
        $query = MySQL::getInstance()->prepare("SELECT userName FROM AuthUser WHERE email=:email");
        $query->bindValue(':email', $email);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['userName'];
    }

    public static function getUserNameByID($userID) {
        $query = MySQL::getInstance()->prepare("SELECT userName FROM AuthUser WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['userName'];
    }

    public static function getUserIDByUserName($userName) {
        $query = MySQL::getInstance()->prepare("SELECT userID FROM AuthUser WHERE userName=:userName");
        $query->bindValue(':userName', $userName);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['userID'];
    }

    public static function getUserUserByID($userID) {
        $query = MySQL::getInstance()->prepare("SELECT userName FROM AuthUser WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['userID'];
    }

    public static function getParentUserID($userID) {
        $query = MySQL::getInstance()->prepare("SELECT parentUserID FROM AuthUser WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['parentUserID'];
    }

    public static function getAccountLocked($userID) {
        $query = MySQL::getInstance()->prepare("SELECT accountLocked FROM AuthUser WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return (bool) $temp['accountLocked'];
    }

    /*
     *      Delete
     */
    public static function delAuthUser($userID) {
        $query = MySQL::getInstance()->prepare("DELETE FROM AuthUser WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        return $query->execute();
    }


    // admin

    public static function getAllUserData() {
        $query = MySQL::getInstance()->prepare("SELECT userID AS id, userName, email, phone, emailConfirmed, phoneConfirmed, emailNotifications, twoFactorType, securityQuestion, securityAnswer, failedLoginCount, UNIX_TIMESTAMP(failedLoginTime) AS failedLoginTime, extraKey, extraKeyType, baseLang, UNIX_TIMESTAMP(extraKeyCreated) AS extraKeyCreated, UNIX_TIMESTAMP(accountCreated) AS accountCreated, accountLocked FROM AuthUser ORDER BY userID");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateUser($userID, $accountLocked, $emailNotifications, $emailConfirmed, $phoneConfirmed) {
        $query = MySQL::getInstance()->prepare("UPDATE AuthUser SET accountLocked=:accountLocked, emailNotifications=:emailNotifications, emailConfirmed=:emailConfirmed, phoneConfirmed=:phoneConfirmed WHERE userID=:userID");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':accountLocked', $accountLocked);
        $query->bindValue(':emailNotifications', $emailNotifications);
        $query->bindValue(':emailConfirmed', $emailConfirmed);
        $query->bindValue(':phoneConfirmed', $phoneConfirmed);
        return $query->execute();
    }
}