<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Data\AvailableFactorsData;
use \PHP_REST_API\Data\AuthUserRolesData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class SystemVariables extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            $availableFactors = AvailableFactorsData::getAvailableFactors();
            foreach($availableFactors as $key => $value) {
                $availableFactors[$key]['available'] = ($value['available'] == "1");
            }
            $availableRoles = AuthUserRolesData::getAvailableRoles();

            echo json_encode(StatusReturn::S200(Array("availableFactors" => $availableFactors, "availableRoles" => $availableRoles)));
        }
    }
}