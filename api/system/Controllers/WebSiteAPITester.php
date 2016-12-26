<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\BaseAPIController;

class WebSiteAPITester extends BaseAPIController {
    function get() {
        if ($this->checkAuth()) {
            include_once(__DIR__ . '/../Views/wapi-tester.html');
        }
    }
}