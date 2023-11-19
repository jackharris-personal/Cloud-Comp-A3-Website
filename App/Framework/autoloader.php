<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - forum
 * Last Updated - 11/09/2023
 */

const APP =  ROOT . 'App'.DIRECTORY_SEPARATOR;
const FRAMEWORK  = APP . 'Framework'.DIRECTORY_SEPARATOR;
const CONTROLLERS = APP.'Controllers'.DIRECTORY_SEPARATOR;
const MODELS = APP.'Models'.DIRECTORY_SEPARATOR;
const ROUTES = ROOT .'Routes'.DIRECTORY_SEPARATOR;
const FACADES = APP .'Facades'.DIRECTORY_SEPARATOR;
const VIEWS = APP .'Views'.DIRECTORY_SEPARATOR;
const MIDDLEWARE = APP.'Middleware'.DIRECTORY_SEPARATOR;
const SERVICES = APP .'Services'.DIRECTORY_SEPARATOR;

$modules = [ROOT,APP,FRAMEWORK,CONTROLLERS,MODELS,VIEWS, FACADES,SERVICES,MIDDLEWARE];

set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR,$modules));

spl_autoload_register(function($class) {
    include  str_replace('\\', '/', $class) . '.php';
});

require_once FRAMEWORK."Database".DIRECTORY_SEPARATOR."constants.php";
