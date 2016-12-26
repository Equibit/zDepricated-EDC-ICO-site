<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\BaseAPIController;

class WebAPITester extends BaseAPIController {
    function get() {
        if ($this->checkAuth()) {
            include_once(__DIR__ . '/../Views/api-tester.html');
        }
    }
}