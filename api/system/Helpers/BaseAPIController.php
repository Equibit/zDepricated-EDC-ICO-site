<?php
namespace PHP_REST_API\Helpers;

/*
 * Class that all controllers classes extends from
 */
class BaseAPIController {
    private $authPassed;

    public function __construct($authPassed) {
        $this->authPassed = $authPassed;
    }

    public function checkAuth() {
        if (_ALLOW_CORS_) $this->getCORSHeaders();
        if (!$this->authPassed) echo json_encode(StatusReturn::E401('401 Not Authorized!'));
        return $this->authPassed;
    }

    public function options() {
        if (_ALLOW_CORS_) {
            $this->getCORSHeaders();
            echo json_encode(StatusReturn::S200('All Clear'));
        } else {
            echo json_encode(StatusReturn::E404());
        }
    }

    public function getCORSHeaders() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Auth-Signature, Auth-User, Auth-Timestamp, X-Requested-With");
        header("Access-Control-Expose-Headers: Auth-Second-Factor, Auth-Secret, Auth-Challenge, Auth-Salt");
        header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
    }
}
