<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\AuthUserRolesData;
use \PHP_REST_API\Data\AuthUserSessionsData;
use \PHP_REST_API\Data\AuthUserSuccessfulIPsData;
use \PHP_REST_API\Data\ManageAPIKeysData;
use \PHP_REST_API\Helpers\TwoFactor;

class AuthUserModel {
    private $userData;
    private $userRoles;
    private $userSession;
    private $userAPIKeys;
    private $authAPIKeyUsed;
    private $activeSessionSecret;

    public function __construct() {}

    public function createUser($authUser, $authEmail, $authPhone, $authPass, $authQuestion, $authAnswer, $extraKey, $lang, $confirm, $ref = null) {
        $emailExists = AuthUserData::emailExist($authEmail);
        $phoneExists = AuthUserData::phoneExist($authPhone);
        $userExists = AuthUserData::userExist($authUser);

        if (_EMAIL_CONFIRMATION_ || _PHONE_CONFIRMATION_) {
            if ($userExists && ($emailExists || $phoneExists) && $extraKey != '') {
                $this->loadUser($authUser);

                if (!$this->checkKey($extraKey, 'SignUp')) {
                    AuthUserData::clearExtraKey($this->userData['userID']);
                    return false;
                }
                if ($this->userData['password'] != $authPass || $this->userData['securityQuestion'] != $authQuestion || $this->userData['securityAnswer'] != $authAnswer) {
                    AuthUserData::clearExtraKey($this->userData['userID']);
                    return false;
                }

                $this->createAndUpdatePassword($authPass);
                AuthUserData::clearExtraKey($this->userData['userID']);
                if (_EMAIL_CONFIRMATION_ && _PHONE_CONFIRMATION_) {
                    if ($confirm == 'email') AuthUserData::confirmEmail($this->userData['userID']);
                    else if ($confirm == 'phone') AuthUserData::confirmPhone($this->userData['userID']);
                }
                else if (_EMAIL_CONFIRMATION_) AuthUserData::confirmEmail($this->userData['userID']);
                else if (_PHONE_CONFIRMATION_) AuthUserData::confirmPhone($this->userData['userID']);

                return true;
            } else if (!$userExists && !$emailExists && !$phoneExists && $extraKey == '') {
                $newExtraKey = null;
                if (_EMAIL_CONFIRMATION_ && _PHONE_CONFIRMATION_) {
                    if ($confirm == 'email') $newExtraKey = $this->createPin(_PIN_SIGN_UP_PLUS_CHARS_);
                    else if ($confirm == 'phone') $newExtraKey = $this->createPin(0, true);
                }
                else if (_EMAIL_CONFIRMATION_) $newExtraKey = $this->createPin(_PIN_SIGN_UP_PLUS_CHARS_);
                else if (_PHONE_CONFIRMATION_) $newExtraKey = $this->createPin(0, true);
                $salt = bin2hex(mcrypt_create_iv(_PASSWORD_SALT_IV_SIZE_, MCRYPT_DEV_URANDOM));
                $newUserID = AuthUserData::addNewUser($authUser, $authEmail, $authPhone, $authPass, $salt, $authQuestion, mb_strtolower($authAnswer), $ref);
                $this->setLanguage($lang);

                $this->loadUserForced($authUser);
                AuthUserRolesData::addUserRole($newUserID, AuthUserRolesData::getRoleID('i18nUser'));
                AuthUserData::updateExtraKey($newUserID, $newExtraKey, 'SignUp');
                if (_EMAIL_CONFIRMATION_ && _PHONE_CONFIRMATION_) {
                    if ($confirm == 'email') $this->sendEmailNotification('SignUp', Array(Array('{{PIN}}'), Array($newExtraKey)));
                    else if ($confirm == 'phone') $this->sendSMSNotification('SignUp', Array(Array('{{PIN}}'), Array($newExtraKey)));
                }
                else if (_EMAIL_CONFIRMATION_) $this->sendEmailNotification('SignUp', Array(Array('{{PIN}}'), Array($newExtraKey)));
                else if (_PHONE_CONFIRMATION_) $this->sendSMSNotification('SignUp', Array(Array('{{PIN}}'), Array($newExtraKey)));
                header('Auth-Second-Factor: true');

                return true;
            }
        } else {
            $salt = bin2hex(mcrypt_create_iv(_PASSWORD_SALT_IV_SIZE_, MCRYPT_DEV_URANDOM));
            $newUserID = AuthUserData::addNewUser($authUser, $authEmail, $authPhone, $authPass, $salt, $authQuestion, mb_strtolower($authAnswer));
            $this->loadUserForced($authUser);
            $this->createAndUpdatePassword($authPass);
            $this->loadUserForced($authUser);
            AuthUserRolesData::addUserRole($newUserID, AuthUserRolesData::getRoleID('i18nUser'));

            return true;
        }

        return false;
    }

    public function changeEmail($newEmail, $emailedCode) {
        if ($emailedCode == '') {
            $newExtraKey = $this->createPin(_PIN_SIGN_UP_PLUS_CHARS_);
            $this->userData['email'] = $newEmail;
            AuthUserData::updateExtraKey($this->userData['userID'], $newExtraKey, 'ChangeEmail (not working?)', $newEmail);
            $this->sendEmailNotification('ChangeEmail (not working?)', Array(Array('{{PIN}}'), Array($newExtraKey)));
            header('Auth-Second-Factor: true');
            return true;
        } else if (!$this->checkKey($emailedCode, 'ChangeEmail (not working?)', $newEmail)) {
            AuthUserData::clearExtraKey($this->userData['userID']);
            return false;
        } else if ($this->checkKey($emailedCode, 'ChangeEmail (not working?)', $newEmail)) {
            AuthUserData::clearExtraKey($this->userData['userID']);
            AuthUserData::updateEmail($this->userData['userID'], $newEmail);
            AuthUserData::confirmEmail($this->userData['userID']);
            return true;
        }

        return false;
    }

    public function changePhone($newPhone, $phoneCode) {
        if ($phoneCode == '') {
            $newExtraKey = $this->createPin(0, true);
            $this->userData['phone'] = $newPhone;
            AuthUserData::updateExtraKey($this->userData['userID'], $newExtraKey, 'ChangePhone', $newPhone);
            $this->sendSMSNotification('ChangeEmail (not working?)', Array(Array('{{PIN}}'), Array($newExtraKey)), $newPhone);
            header('Auth-Second-Factor: true');
            return true;
        } else if (!$this->checkKey($phoneCode, 'ChangePhone', $newPhone)) {
            AuthUserData::clearExtraKey($this->userData['userID']);
            return false;
        } else if ($this->checkKey($phoneCode, 'ChangePhone', $newPhone)) {
            AuthUserData::clearExtraKey($this->userData['userID']);
            AuthUserData::updatePhone($this->userData['userID'], $newPhone);
            AuthUserData::confirmPhone($this->userData['userID']);
            return true;
        }

        return false;
    }

    public function forgotPassword($userOrEmail, $secondFactor, $answer, $newPassword) {
        if (AuthUserData::emailExist($userOrEmail)) {
            $userAuth = AuthUserData::getUserNameByEmail($userOrEmail);
            $this->loadUserForced($userAuth);
        } else if (AuthUserData::userExist($userOrEmail)) {
            $userAuth = $userOrEmail;
            $this->loadUserForced($userAuth);
        } else {
            return Array("continue" => false);
        }

        if ($secondFactor != '' || (!_EMAIL_CONFIRMATION_ && !_PHONE_CONFIRMATION_)) {
            if ((!_EMAIL_CONFIRMATION_ && !_PHONE_CONFIRMATION_) || $this->checkKey(mb_strtoupper($_POST['secondFactor']), 'forgotPassword')) {
                if ($answer != '') {
                    if (mb_strtolower($answer) == $this->userData['securityAnswer']) {
                        if ($newPassword != '' && $newPassword != hash('sha512', '')) {
                            $this->createAndUpdatePassword($newPassword);
                            AuthUserData::clearExtraKey($this->userData['userID']);
                            if (_EMAIL_CONFIRMATION_ && !_PHONE_CONFIRMATION_ && !$this->userData['emailConfirmed']) AuthUserData::confirmEmail($this->userData['userID']);
                            if (_PHONE_CONFIRMATION_ && !_EMAIL_CONFIRMATION_ && !$this->userData['phoneConfirmed']) AuthUserData::confirmPhone($this->userData['userID']);
                            return Array("continue" => true, "flowDone" => true);
                        } else {
                            return Array("continue" => true, "askForNewPassword" => true);
                        }
                    }
                } else {
                    return Array("continue" => true, "question" => $this->userData['securityQuestion']);
                }
            } else {
                AuthUserData::clearExtraKey($this->userData['userID']);
            }
        } else {
            if (_EMAIL_CONFIRMATION_ && $this->userData['emailConfirmed']) {
                $newExtraKey = $this->createPin(_PIN_FORGOT_PASSWORD_PLUS_CHARS_);
                AuthUserData::updateExtraKey($this->userData['userID'], $newExtraKey, 'forgotPassword');
                $this->sendEmailNotification('ForgotPassword', Array(Array('{{PIN}}'), Array($newExtraKey)));
                return Array("continue" => true, "secondFactor" => true);
            } else if (_PHONE_CONFIRMATION_ && $this->userData['phoneConfirmed']) {
                $newExtraKey = $this->createPin(0, true);
                AuthUserData::updateExtraKey($this->userData['userID'], $newExtraKey, 'forgotPassword');
                $this->sendSMSNotification('ForgotPassword', Array(Array('{{PIN}}'), Array($newExtraKey)));
                return Array("continue" => true, "secondFactor" => true);
            } else if (_EMAIL_CONFIRMATION_) {
                $newExtraKey = $this->createPin(_PIN_FORGOT_PASSWORD_PLUS_CHARS_);
                AuthUserData::updateExtraKey($this->userData['userID'], $newExtraKey, 'forgotPassword');
                $this->sendEmailNotification('ForgotPassword', Array(Array('{{PIN}}'), Array($newExtraKey)));
                return Array("continue" => true, "secondFactor" => true);
            } else if (_PHONE_CONFIRMATION_) {
                $newExtraKey = $this->createPin(0, true);
                AuthUserData::updateExtraKey($this->userData['userID'], $newExtraKey, 'forgotPassword');
                $this->sendSMSNotification('ForgotPassword', Array(Array('{{PIN}}'), Array($newExtraKey)));
                return Array("continue" => true, "secondFactor" => true);
            } else {
                return Array("continue" => true, "secondFactor" => false, "question" => $this->userData['securityQuestion']);
            }
        }
        return Array("continue" => false);
    }

    public function createAndUpdatePassword($newPassword) {
        $this->userData['salt'] = bin2hex(mcrypt_create_iv(_PASSWORD_SALT_IV_SIZE_, MCRYPT_DEV_URANDOM));
        $this->userData['password'] = hash_pbkdf2('sha512', $newPassword, $this->userData['salt'], 1000);

        return AuthUserData::updatePasswordAndSalt($this->userData['userID'], $this->userData['password'], $this->userData['salt']);
    }

    public function checkKey($key, $type, $data = null) {
        if (!is_null($data) && $data != $this->userData['extraKeyData']) return false;
        return (mb_strtoupper($key) == mb_strtoupper($this->userData['extraKey']) && $this->userData['extraKeyType'] == $type && ($this->userData['extraKeyCreated'] + _SECOND_FACTOR_EXPIRE_SECONDS_) > time());
    }

    public function loadUser($authUser, $initialize = false) {
        if (AuthUserData::userExist($authUser)) {
            $this->loadUserForced($authUser);

            if (AuthUserData::userExistConfirmed($authUser)) {
                if ($initialize) {
                    return true;
                }
                return $this->findCurrentSession();
            }
        }

        return false;
    }

    public function loadAPIUser($authKey) {
        $userID = ManageAPIKeysData::getUserIDByPublicKey($authKey);
        if ($this->loadUserForced(AuthUserData::getUserNameByID($userID))) {
            $this->authAPIKeyUsed = $authKey;
            return true;
        }

        return false;
    }

    public function loadUserForced($authUser) {
        if (AuthUserData::userExist($authUser)) {
            $this->userData = AuthUserData::getUserData($authUser);
            $this->getUserRoles();
            $this->getUserSessions();
            $this->getAPIKeys();
            return true;
        } else {
            return false;
        }
    }

    public static function createPin($baseLen = 0, $numbersOnly = false) {
        $useChars = _CHARS_FOR_SECOND_FACTOR_KEYS_;
        if ($numbersOnly) $useChars = _NUMBERS_FOR_SECOND_FACTOR_KEYS_;

        $characters = str_shuffle($useChars);
        $charLen = strlen($characters) - 1;
        $len = mt_rand($baseLen+_PIN_LOWEST_NUMBER_OF_CHARS_, $baseLen+_PIN_HIGH_RANGE_NUMBER_OF_CHARS_);

        $string = '';
        for ($i = 0; $i < $len; $i++) $string .= $characters[mt_rand(0, $charLen)];
        return mb_strtoupper($string);
    }

    public function findCurrentSession() {
        forEach ($this->userSession AS $value) {
            if (true) {
                $this->activeSessionSecret = $value['sessionSecret'];
                AuthUserSessionsData::updateSessionActivity($value['sessionID']);
                return true;
            }
        }
        return false;
    }

    public function createNewSession($createdSecret) {
        $this->activeSessionSecret = $createdSecret;
        return AuthUserSessionsData::addNewSession($this->userData['userID'], $createdSecret, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
    }

    public function sendEmailNotification($type, $extraVariables = null, $language = null) {
        if ($type == 'Notification' && !$this->userData['emailNotifications']) return true;
        $emailTemplate = new EmailTemplatesModel(Array($this->userData['email']), $type, (is_null($language) || $language == '' ? $this->userData['baseLang'] : $language));
        $emailTemplate->addVariables($extraVariables);
        return $emailTemplate->send();
    }

    public function sendSMSNotification($type, $extraVariables = null, $language = null) {
        if ($type == 'Notification' && !$this->userData['phoneNotifications']) return true;
        $smsTemplate = new SMSMessagesModel(Array($this->userData['phone']), $type, (is_null($language) || $language == '' ? $this->userData['baseLang'] : $language));
        $smsTemplate->addVariables($extraVariables);
        return $smsTemplate->send();
    }

    public function sendAllNotification($type, $extraVariables = null) {
        if ($this->userData['emailConfirmed']) $this->sendEmailNotification($type, $extraVariables);
        if ($this->userData['phoneConfirmed']) $this->sendSMSNotification($type, $extraVariables);
    }

    public function sendNotification($type, $extraVariables = null) {
        if ($this->userData['twoFactorType'] == TwoFactor::SMS) {
            $this->sendSMSNotification($type, $extraVariables);
        } else {
            $this->sendEmailNotification($type, $extraVariables);
        }
    }

    public function askClientChallenge() {
        $challengePin = $this->createPin(30);
        AuthUserData::updateExtraKey($this->userData['userID'], $challengePin, 'challenge');
        header('Auth-Challenge: ' . $challengePin);
        header('Auth-Salt: ' . $this->userData['salt']);
    }

    public function initiateConnection() {
        $createdSecret = $this->createNewSecret();

        if ($this->userData['twoFactorType'] == TwoFactor::Email) {
            $secondFactor = $this->createPin();
            header('Auth-Second-Factor: true');
            $createdSecret = base64_encode(hash_hmac('sha512', $createdSecret, $secondFactor, true));
            $this->sendNotification('Login2fa', Array(Array('{{PIN}}'), Array($secondFactor)));
        } else if ($this->userData['twoFactorType'] == TwoFactor::SMS) {
            $secondFactor = $this->createPin(0, true);
            header('Auth-Second-Factor: true');
            $createdSecret = base64_encode(hash_hmac('sha512', $createdSecret, $secondFactor, true));
            $this->sendSMSNotification('Login2fa', Array(Array('{{PIN}}'), Array($secondFactor)));
        }

        $this->createNewSession($createdSecret);
        return true;
    }

    public function createNewSecret() {
        AuthUserData::clearExtraKey($this->userData['userID']);
        $createdSecret = base64_encode(hash('sha512', sha1(microtime(true).mt_rand(10000,90000))));
        header('Auth-Secret: ' . $createdSecret);
        return $createdSecret;
    }

    public function notifyOnFailedLogin() {
        if ($this->userData['failedLoginCount'] >= _LOGIN_FAILED_COUNT_BEFORE_NOTIFICATION_) {
            AuthUserData::resetFailedLogin($this->userData['userID']);
            if ($this->userData['failedLoginTime'] + _LOGIN_FAILED_TIME_BEFORE_RESET_COUNT_SECONDS_ > time()) {
                $this->sendAllNotification('LoginFailed');
            }
            return true;
        }
        return false;
    }

    public function addFailedLogin() {
        if (!$this->notifyOnFailedLogin()) {
            if ((is_null($this->userData['failedLoginCount']) && is_null($this->userData['failedLoginTime'])) || $this->userData['failedLoginTime'] + _LOGIN_FAILED_TIME_BEFORE_RESET_COUNT_SECONDS_ < time()) {
                AuthUserData::updateFailedLogin($this->userData['userID'], 1, time());
            } else {
                AuthUserData::updateFailedLogin($this->userData['userID'], ($this->userData['failedLoginCount'] + 1), $this->userData['failedLoginTime']);
            }
        }
    }

    public function makeSuccessfulLogin($initialize = false) {
        if ($initialize) $this->addSuccessfulIP();
        AuthUserData::resetFailedLogin($this->userData['userID']);
    }

    public function addSuccessfulIP() {
        AuthUserSuccessfulIPsData::addSuccessfulIP($this->userData['userID'], $_SERVER['REMOTE_ADDR']);
    }

    public function isLocked() {
        if (!is_null($this->userData['parentUserID'])) {
            return AuthUserData::getAccountLocked($this->userData['parentUserID']);
        } else {
            return AuthUserData::getAccountLocked($this->userData['userID']);
        }
    }

    public function isUserAdmin() {
        if (is_null($this->userRoles)) return false;

        foreach ($this->userRoles AS $role) {
            if ($role == 'i18nAdmin') return true;
        }

        return false;
    }

    /*
     *      Getters
     */
    public function getUserID() {
        return $this->userData['userID'];
    }

    public function getUserLang() {
        return $this->userData['baseLang'];
    }

    public function getSettings() {
        return Array("baseLang" => $this->userData['baseLang'], "twoFactorType" => $this->userData['twoFactorType'], "emailNotifications" => $this->userData['emailNotifications'], "phoneNotifications" => $this->userData['phoneNotifications'], "emailConfirmed" => $this->userData['emailConfirmed'], "phoneConfirmed" => $this->userData['phoneConfirmed'], "roles" => $this->userRoles);
    }

    public function getChallengeKey() {
        if ($this->userData['extraKeyType'] == 'challenge') {
            // resets keys so this challenge cannot be retested
            AuthUserData::clearExtraKey($this->userData['userID']);
            return mb_strtoupper($this->userData['extraKey']);
        }
        return null;
    }

    public function getUserData() {
        return $this->userData;
    }

    public function getUserPassword() {
        return $this->userData['password'];
    }

    public function getSalt() {
        return $this->userData['salt'];
    }

    public function getUserSecret() {
        return $this->activeSessionSecret;
    }

    public function getAPISecret() {
        return ManageAPIKeysData::getSecretByPublicKey($this->userData['userID'], $this->authAPIKeyUsed);
    }

    public function getUserRoles() {
        if (is_null($this->userRoles)) {
            $roles = AuthUserRolesData::getUserRoles($this->userData['userID']);
            foreach ($roles AS $value) {
                $this->userRoles[] = $value['roleName'];
            }
        }
        return $this->userRoles;
    }

    public function getUserSessions() {
        if (is_null($this->userSession)) {
            AuthUserSessionsData::delExpiredSessions($this->userData['userID']);
            $this->userSession = AuthUserSessionsData::getUserSessions($this->userData['userID']);
        }
        return $this->userSession;
    }

    public function getAPIKeys() {
        $this->userAPIKeys = ManageAPIKeysData::getManageAPIKeysData($this->userData['userID']);
    }

    public function getManageUsersData() {
        $groupUserID = (!is_null($this->userData['parentUserID']) ? $this->userData['parentUserID'] : $this->userData['userID']);
        $this->userData['myUsers'] = AuthUserData::getUsersByParent($groupUserID);

        foreach ($this->userData['myUsers'] as $key => $user) {
            $roles = AuthUserRolesData::getUserRoles($this->userData['myUsers'][$key]['id']);
            $dataBlock['myUsers'][$key]['roles'] = Array();
            foreach ($roles AS $value) {
                $this->userData['myUsers'][$key]['roles'][] = $value['roleName'];
            }
        }
        return $this->userData['myUsers'];
    }

    public function getManageUserData($userID) {
        if (!isset($this->userData['myUsers'])) {
            $this->getManageUsersData();
        }
        foreach ($this->userData['myUsers'] AS $key => $user) {
            if ($this->userData['myUsers'][$key]['userName'] == AuthUserData::getUserUserByID($userID)) return $this->userData['myUsers'][$key];
        }
        return null;
    }

    /*
     *      Setters
     */
    public function setSettings($baseLang, $secondFactor, $emailNotifications, $phoneNotifications) {
        $this->setLanguage($baseLang);
        AuthUserData::updateFactor($this->userData['userID'], $secondFactor);
        if ($emailNotifications && !$this->userData['emailConfirmed']) $emailNotifications = 0;
        if ($phoneNotifications && !$this->userData['phoneConfirmed']) $phoneNotifications = 0;
        AuthUserData::updateNotifications($this->userData['userID'], $emailNotifications, $phoneNotifications);
        return true;
    }

    public function setLanguage($lang) {
        AuthUserData::updateLanguage($this->userData['userID'], $lang);
    }

    public function setPassword($current, $newPassword) {
        $compare = hash_pbkdf2('sha512', $current, $this->userData['salt'], 1000);
        if ($compare === $this->userData['password']) {
            $this->createAndUpdatePassword($newPassword);

            return true;
        }
        return false;
    }

    public function setQuestion($question, $answer) {
        AuthUserData::updateSecurityQuestion($this->userData['userID'], $question, $answer);
        return true;
    }
}
