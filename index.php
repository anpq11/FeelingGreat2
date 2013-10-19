<?php

/**
 * All requests routed through here. This is an overview of what actaully happens during
 * a request.
 *
 * @package LydiaCore
 */

// ---------------------------------------------------------------------------------------
//
// PHASE: BOOTSTRAP
//
define('FeelingGreat2_INSTALL_PATH', dirname(__FILE__));
define('FeelingGreat2_SITE_PATH', FeelingGreat2_INSTALL_PATH . '/site');

require(FeelingGreat2_INSTALL_PATH.'/src/bootstrap.php');

$mg = CFeelingGreat2::Instance();


// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//
$mg->FrontControllerRoute();


// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
$mg->ThemeEngineRender();