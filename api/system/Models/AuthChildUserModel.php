<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\AuthUserRolesData;
use \PHP_REST_API\Data\AuthUserSessionsData;
use \PHP_REST_API\Data\AuthUserSuccessfulIPsData;

class AuthChildUserModel {
    private $parentUserID;
    private $userID;
    private $validated = false;

    public function __construct($parentUserName, $userID = null) {
        $this->parentUserID = AuthUserData::getUserIDByUserName($parentUserName);
        
        if (!is_null($userID)) {
            $this->userID = $userID;
            if (AuthUserData::getParentUserID($userID) == $this->parentUserID) $this->validated = true;
        }
    }

    public function createSubUser($userName, $email, $newPassword, $twoFactorType, $roles) {
        if (!$this->validated) {
            $this->userID = AuthUserData::addSubNewUser($userName, $email, $this->parentUserID);
            AuthUserData::confirmEmail(AuthUserData::getUserIDByUserName($userName));
            $this->validated = true;

            $this->updateSubUserPassword($newPassword);
            $this->updateSubUser($twoFactorType, $roles);

            return $this->userID;
        }
        return false;
    }
    
    public function updateSubUserFactor($twoFactorType) {
        if ($this->validated) {
            return AuthUserData::updateFactor($this->userID, (int)$twoFactorType);
        }
        return false;
    }
    
    public function updateSubUserRoles($roles) {
        if ($this->validated) {
            AuthUserRolesData::delAuthUserRoles($this->userID);
            foreach ($roles AS $value) {
                if (AuthUserRolesData::isRole($value)) AuthUserRolesData::addUserRole($this->userID, AuthUserRolesData::getRoleID($value));
            }

            return true;
        }
        return false;
    }
    
    public function updateSubUserPassword($newPassword) {
        if ($this->validated) {
            $newSalt = bin2hex(mcrypt_create_iv(_PASSWORD_SALT_IV_SIZE_, MCRYPT_DEV_URANDOM));
            $newPassword = hash_pbkdf2('sha512', $newPassword, $newSalt, 1000);

            AuthUserData::updatePasswordAndSalt($this->userID, $newPassword, $newSalt);
            
            return true;
        }
        return false;
    }

    public function updateSubUser($twoFactorType, $roles) {
        if ($this->validated) {
            $this->updateSubUserRoles($roles);
            $this->updateSubUserFactor($twoFactorType);

            return true;
        }
        return false;
    }

    /*
     *      Deletes
     */
    public function delUser() {
        if ($this->validated) {
            AuthUserData::delAuthUser($this->userID);
            AuthUserRolesData::delAuthUserRoles($this->userID);
            AuthUserSessionsData::delAuthUserSessions($this->userID);
            AuthUserSuccessfulIPsData::delAuthUserSuccessfulIPs($this->userID);

            return true;
        }
        return false;
    }
}