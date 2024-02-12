<?PHP 

/**
 * Copyright 2016, 2024 5 Mode
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
 * index-js.php
 * 
 * Simplicity index file js.
 *
 * @author Daniele Bonini <my25mb@aol.com>
 * @copyrights (c) 2016, 2024 5 Mode     
 */

 require "../../Private/core/init.inc";

 header("Content-Type: text/javascript");

 // PARAMETERS AND VARIABLES INIT
 
 $lang = APP_DEF_LANG;
 $lang1 = substr(filter_input(INPUT_GET, "hl")??"", 0, 5);
 $lang1= strip_tags($lang1);
 if ($lang1 !== PHP_STR) {
   $lang = $lang1;
 }
 $shortLang = getShortLang($lang);
 
 $CURRENT_VIEW=filter_input(INPUT_GET, "cv")??"";
 $CURRENT_VIEW= strip_tags($CURRENT_VIEW);
 
?>  

function startApp() {
    hidePassword();
  }			

  function hidePassword() {
    $("#passworddisplay").css("visibility","hidden");
  }  

  /*
   * call to startApp
   * 
   * @returns void
   */
  function _startApp() {
    setTimeout("startApp()", 1000);    
  }

 /*
  *  Display the current hash for the config file
  *  
  *  @returns void
  */
 function showEncodedPassword() {
   if ($("#Password").val() === "") {
     $("#Password").addClass("emptyfield");
     return;  
   }
   //if ($("#Salt").val() === "") {
   //  $("#Salt").addClass("emptyfield");
   //  return;  
   //}	   	
   passw = encryptSha2( $("#Password").val() + $("#Salt").val());
   msg = "<?PHP echo(getResource0("Please set your hash in the config file with this value", $lang, "/js/home-js.php"));?>:";
   alert(msg + "\n\n" + passw);	
 }

 function passwordSubmit() {
    $("#_group").val("");
    frmSim.submit();
 }
 
$("input#Password").on("keydown",function(e){
    key = e.which;
    if (key===13) {
        e.preventDefault();
        passwordSubmit();
    }
 });
 
 window.addEventListener("load", function() {
   setTimeout("_startApp()", 10000);
 });  
