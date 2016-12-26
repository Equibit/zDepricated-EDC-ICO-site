<?php
namespace PHP_REST_API;

use \PHP_REST_API\Models\AuthUserModel;

/*
 * Authentication Check
 */
class ApiAuthCheck {
    public static function checkAuth($roles, $initialize = false, $whenLocked = false, $isAPI = false) {
        $headers = getallheaders();
        if ((!$isAPI && !isset($headers['Auth-User']) || ($isAPI && !isset($headers['Auth-Key']))) || !isset($headers['Auth-Timestamp']) || !isset($headers['Auth-Signature'])) return false;

        if (!is_numeric($headers['Auth-Timestamp']) || $headers['Auth-Timestamp'] < strtotime("-" . _CALL_TIME_TO_LIVE_IN_MINUTES_ . " minute", time())) return false;

        if (_USE_HTTPS_ONLY_ && empty($_SERVER['HTTPS'])) return false;

        $userSecret = null;
        $userData = new AuthUserModel();

        if (!$isAPI) {

            if (!$userData->loadUser(mb_strtolower($headers['Auth-User']), $initialize)) return false;
            if ($userData->isLocked() && !$whenLocked) return false;

            if ($initialize) {
                $userSecret = $userData->getUserPassword();
                $challenge = $userData->getChallengeKey();

                if (!array_key_exists('challenge', $_POST)) {
                    $userData->askClientChallenge();
                    return true;
                } else if ($_POST['challenge'] != $challenge) {
                    $userData->addFailedLogin();
                    return false;
                } else if ($_POST['challenge'] == $challenge) {
                    $userData->initiateConnection();
                }
            } else {
                $userSecret = $userData->getUserSecret();
            }

        } else {

            if ($userData->isLocked() && !$whenLocked) return false;
            if (!$userData->loadAPIUser(mb_strtolower($headers['Auth-Key']))) return false;
            $userSecret = $userData->getAPISecret();

        }

        $newAuthSignature = ApiAuthCheck::signData($userSecret, $headers);

        if (hash_equals($newAuthSignature, $headers['Auth-Signature']) && !empty(array_intersect($userData->getUserRoles(), $roles))) {
            $userData->makeSuccessfulLogin($initialize);
            return true;
        }

        // initiate connection add secret, but the hash test needs to pass, so if it fails, remove secret and 2nd factor header.
        header_remove('Auth-Secret');
        header_remove('Auth-Second-Factor');
        if ($initialize) $userData->addFailedLogin();
        return false;
    }

    private static function signData($userSecret, $headers) {
        $data = '';
        foreach ($_POST AS $key => $value) {
            if ($data != "") $data .= "&";
            if (is_array($value)) {
                $currentCount = 0;
                $data .= $key . '=';
                foreach ($value AS $arrValue) {
                    $currentCount++;
                    $data .= $arrValue;
                    if (count($value) > 1 && $currentCount != count($value)) $data .= ',';
                }
            } else {
                $data .= $key . '=' . $value;
            }
        }
        $signatureData = $_SERVER['REQUEST_METHOD'] . _DOMAIN_API_HOST_ . $_SERVER['REQUEST_URI'] . $data . $headers['Auth-Timestamp'];
        return base64_encode(hash_hmac('sha512', $signatureData, $userSecret, true));
    }
}
