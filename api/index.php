<?php
/**
 * PHP-REST-API-JS - PHP Restful API using custom MVC style structure
 * PHP Version 5.6.18
 * @package PHP-REST-API
 * @author Marc Godard <godardm@gmail.com>
 * @copyright 2016 Marc Godard
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

use PHP_REST_API\ApiAuthRouter;
use PHP_REST_API\ApiAuthRouterHook;
use PHP_REST_API\Helpers\StatusReturn;

/* Web sockets */
use Ratchet\App;
use React\EventLoop;
use WebSockets\Notifications;
use WebSockets\Chat;
use Ratchet\Server\EchoServer;

error_reporting(E_ALL);
date_default_timezone_set('UTC');

require_once('system/Constants.php');

/* AutoLoaders */
require_once('system/Libraries/autoload.php');
require_once("system/AutoLoader.php");

if (!isset($argv[1])) {
    ApiAuthRouterHook::add("404", function() {
        echo json_encode(StatusReturn::E404('404 Not Found!'));
    });

    ApiAuthRouterHook::add("404Web", function() {
        StatusReturn::WEB404();
    });

    $controllersArray = Array(
        '/'
        => Array('controller' => 'WebSPA', 'auth' => false),
        '/api-tester/'
        => Array('controller' => 'WebAPITester', 'auth' => false),
        '/wapi-tester/'
        => Array('controller' => 'WebSiteAPITester', 'auth' => false),

        '/bit-pay-ipn/'
        => Array('controller' => 'BitPayIPN', 'auth' => false),

        '/blockchain-ipn/'
        => Array('controller' => 'BlockchainIPN', 'auth' => false),

        '/wapi/check-username/:alphaNumPlus/'
        => Array('controller' => 'CheckUsername', 'auth' => false),

        '/wapi/check-email/:email/'
        => Array('controller' => 'CheckEmail', 'auth' => false),

        '/wapi/check-phone/:num/'
        => Array('controller' => 'CheckPhone', 'auth' => false),

        '/wapi/sign-up/'
        => Array('controller' => 'SignUp', 'auth' => false),

        '/wapi/forgot-password/'
        => Array('controller' => 'ForgotPassword', 'auth' => false),

        '/wapi/faqs/:locale/'
        => Array('controller' => 'FAQs', 'auth' => false),

        '/wapi/blog/:locale/'
        => Array('controller' => 'Blog', 'auth' => false),

        '/wapi/crowd-sale-progress/'
        => Array('controller' => 'CrowdSaleProgress', 'auth' => false),

        '/wapi/initiate/'
        => Array('controller' => 'InitiateConnection', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true, 'initialize' => true),

        '/wapi/check-login/'
        => Array('controller' => 'CheckLogin', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/account-settings/'
        => Array('controller' => 'AccountSettings', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/system-variables/'
        => Array('controller' => 'SystemVariables', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/change-email/'
        => Array('controller' => 'ChangeEmail (not working?)', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/change-phone/'
        => Array('controller' => 'ChangePhone', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/change-password/'
        => Array('controller' => 'ChangePassword', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/change-question/'
        => Array('controller' => 'ChangeSecurityQuestion', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/notifications/:num/'
        => Array('controller' => 'Notifications', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),
        '/wapi/notifications/'
        => Array('controller' => 'Notifications', 'roles' => Array('i18nAdmin', 'i18nUser'), 'whenLocked' => true),

        '/wapi/manage-child-users/:num/'
        => Array('controller' => 'ManageChildUsers', 'roles' => Array('i18nManage'), 'whenLocked' => true),
        '/wapi/manage-child-users/'
        => Array('controller' => 'ManageChildUsers', 'roles' => Array('i18nManage'), 'whenLocked' => true),

        '/wapi/admin-users/:num/'
        => Array('controller' => 'AdminUsers', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-users/'
        => Array('controller' => 'AdminUsers', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-notify/:num/:num/'
        => Array('controller' => 'AdminNotify', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-notify/:num/'
        => Array('controller' => 'AdminNotify', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-faqs/:num/'
        => Array('controller' => 'AdminFAQs', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-faqs/'
        => Array('controller' => 'AdminFAQs', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-blog/:num/'
        => Array('controller' => 'AdminBlog', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-blog/'
        => Array('controller' => 'AdminBlog', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-text-variables/'
        => Array('controller' => 'AdminTextVariables', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-comm-templates/:num/'
        => Array('controller' => 'AdminCommTemplates', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-comm-templates/'
        => Array('controller' => 'AdminCommTemplates', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-comm-templates-email/'
        => Array('controller' => 'AdminCommTemplatesEmail', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-comm-templates-sms/'
        => Array('controller' => 'AdminCommTemplatesSMS', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-send-email/:str/'
        => Array('controller' => 'AdminSendEmail', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-send-sms/:str/'
        => Array('controller' => 'AdminSendSMS', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-poloniex/'
        => Array('controller' => 'AdminPoloniex', 'roles' => Array('i18nAdmin')),

        '/wapi/manage-api-keys/:num/'
        => Array('controller' => 'ManageAPIKeys', 'roles' => Array('i18nManage'), 'whenLocked' => false),
        '/wapi/manage-api-keys/'
        => Array('controller' => 'ManageAPIKeys', 'roles' => Array('i18nManage'), 'whenLocked' => false),

        '/api/v0/test/'
        => Array('controller' => 'CheckLogin', 'roles' => Array('i18nUser'), 'api' => true),

        '/wapi/ico-transactions/'
        => Array('controller' => 'ICOTransaction', 'roles' => Array('i18nUser')),

        '/wapi/admin-bitcoin-transactions/'
        => Array('controller' => 'AdminBitcoinTransaction', 'roles' => Array('i18nAdmin')),

        '/wapi/admin-transactions/:num/'
        => Array('controller' => 'AdminTransaction', 'roles' => Array('i18nAdmin')),
        '/wapi/admin-transactions/'
        => Array('controller' => 'AdminTransaction', 'roles' => Array('i18nAdmin')),
    );

    ApiAuthRouter::serve($controllersArray);
} else if ($argv[1] == 'cron') {

    $cronClass = '\\PHP_REST_API\\CronJobs\\' . $argv[2];
    if (class_exists($cronClass)) {
        $handler_instance = new $cronClass;
    }

} else if ($argv[1] == 'sockets') {

    $loop = React\EventLoop\Factory::create();

    $app = new App(_DOMAIN_NAME_, _WEB_SOCKET_PORT_, '0.0.0.0', $loop);
    $app->route('/notifications', new Notifications($loop), array('*'));
    $app->route('/chat', new Chat($loop), array('*'));
    $app->route('/echo', new EchoServer, array('*'));

    echo "Web socket server is running. Press ctrl-c to exit...\r\n";

    $app->run();

}