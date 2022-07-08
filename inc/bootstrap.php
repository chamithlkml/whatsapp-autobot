<?php
define('PROJECT_ROOT_PATH', __DIR__ . '/..');

date_default_timezone_set('Asia/Colombo');

require_once PROJECT_ROOT_PATH . '/Lib/vendor/autoload.php';
require_once PROJECT_ROOT_PATH . '/inc/config.php';
require_once PROJECT_ROOT_PATH . '/Controller/Api/BaseController.php';
require_once PROJECT_ROOT_PATH . '/Model/WARequestModel.php';
require_once PROJECT_ROOT_PATH . '/Model/OrderModel.php';
require_once PROJECT_ROOT_PATH . '/Model/RoomModel.php';
require_once PROJECT_ROOT_PATH . '/Lib/Logger.php';
require_once PROJECT_ROOT_PATH . '/Lib/SmsGateway.php';
require_once PROJECT_ROOT_PATH . '/Lib/Mailer.php';
