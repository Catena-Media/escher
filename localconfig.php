<?php

/**
 * Your Project Name
 *
 * @copyright 2014 YourCompany
 * @package   \YourCompany\YourProject
 * @license   All Rights Reserved
 */

// // Perform some http-only operations
// $isHttp = strtolower(php_sapi_name()) !== 'cli';
// if ($isHttp) {

//     // Last modified right now
//     \TDM\Escher\HTTP\CurrentRequest::setLastModified(time());

//     // Assume we're returning HTML - we can override this later
//     \TDM\Escher\HTTP\CurrentRequest::setContentType("text/html");
// }

// // Define external paths.
// $vrootdir  = '';
// $vadmindir = "$vrootdir/admin";
// $vimagedir = "$vrootdir/media";

// // Define global template vars
// $templateGlob = array(
//     'vrootdir'      => &$vrootdir,
//     'vadmindir'     => &$vadmindir,
//     'vimagedir'     => &$vimagedir,
//     'copyrightYear' =>  date('Y'),
// );
