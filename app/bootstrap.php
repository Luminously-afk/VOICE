<?php
require_once 'Config/config.php';
require_once 'Config/database.php';

// Composer Autoloader
if (file_exists(dirname(dirname(__FILE__)) . '/vendor/autoload.php')) {
    require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
}

require_once 'Core/Model.php';
require_once 'Core/Controller.php';
require_once 'Core/Spaces.php';
require_once 'Core/App.php';

require_once 'Core/Session.php';
require_once 'Core/Auth.php';
require_once 'Core/Flash.php';

Session::init();