<?php

/**
 * Copyright 2021, 2024 5 Mode
 * All Rights Reserved.
 * 
 * This file is part of Simplicity.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither 5 Mode nor the names of its contributors 
 *       may be used to endorse or promote products derived from this software 
 *       without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS OR CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * init.inc
 * 
 * Simplicity Initialization file.
 *
 * @author Daniele Bonini <my25mb@aol.com>
 * @copyrights (c) 2016, 2024, 5 Mode   
 */

//use fivemode\fivemode\Class;

error_reporting(E_ALL & ~ (E_WARNING | E_NOTICE | E_STRICT | E_DEPRECATED));  
ini_set('display_startup_errors',1);  
ini_set('display_errors',1);  
ini_set('log_errors',1); 


// CONFIGURATION
define("CONFIG_PATH", __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config");

require CONFIG_PATH . DIRECTORY_SEPARATOR . "config.inc";
require CONFIG_PATH . DIRECTORY_SEPARATOR . "const.php.inc";

define("FUNCTIONS_PATH", __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "functions");

require FUNCTIONS_PATH . DIRECTORY_SEPARATOR . "func.string.inc";
require FUNCTIONS_PATH . DIRECTORY_SEPARATOR . "func.various.inc";


mb_internal_encoding("UTF-8");


// ERROR HANDLING AND LOGGING

if (DEBUG) {
  error_reporting(E_ALL | E_STRICT);  
  ini_set('display_startup_errors',1);  
  ini_set('display_errors',1);
  ini_set('log_errors',1); 
} else {
  error_reporting(E_ALL & ~ (E_WARNING | E_NOTICE | E_STRICT | E_DEPRECATED));  
  ini_set('display_startup_errors',0);  
  ini_set('display_errors',0);  
  ini_set('log_errors',1); 
}

//Err::setDefaultHandlers();


// SHUTDOWN 

/**
 * Shutdown callback.
 * 
 * @return void
 */
function shutdownCallback() {
  
//Err::setLogOnlyHandlers();

/*  
  if (Cache::issetInstance()) {  
    //For non-persistent connections only
    //$cache = &Cache::getInstance();
    //$cache->close();

    Cache::unsetInstance();
  }
*/
  
}
register_shutdown_function("shutdownCallback");

// ERROR PARAMETERS

//errNo
//define("ERR_NO", substr(filter_input1(INPUT_GET, "errNo", FILTER_SANITIZE_MD5TOKEN), 0, 100));
//if (ERR_NO!==PHP_STR && !filter_var1(ERR_NO, FILTER_VALIDATE_ERRNO)) {
//  Err::trigger_error1(ERR::ERR_ERR, "Invalid error number: " . ERR_NO, __FILE__, __LINE__);
//}

//errKey
//define("ERR_KEY", array_search(ERR_NO, Err::$A_ERR_NO));

//errMsg
//define("ERR_MSG", substr(filter_input1(INPUT_GET, "errMsg", FILTER_SANITIZE_ERRMSG), 0, 200));

//errScript
//define("ERR_SCRIPT", substr(filter_input(INPUT_GET, "errScript", FILTER_SANITIZE_STRING), 0, 255));

//errLine
//define("ERR_LINE",  substr(filter_input(INPUT_GET, "errLine", FILTER_SANITIZE_NUMBER_INT), 0, 5));

//errStack
//define("ERR_STACK", substr(filter_input1(INPUT_GET, "errStack", FILTER_SANITIZE_STRING_WITHBR), 0, 2000));

?>
