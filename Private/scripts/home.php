<?PHP

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
 * home.php
 * 
 * Simplicity home.
 *
 * @author Daniele Bonini <my25mb@aol.com>
 * @copyrights (c) 2021, 2024, 5 Mode     
 */

 // PAGE PARAMETERS
$groupName = PHP_STR;
$userName = PHP_STR;
$defLocation = PHP_STR;
$defPhone = PHP_STR;
$defStatus2 = PHP_STR;
$defOnline = 0;

$curgroup = filter_input(INPUT_POST, "_group")??""; 
$curgroup = strip_tags($curgroup);

$aGroups = $FRIENDS;

$checkin = filter_input(INPUT_POST, "_checkin")??""; 
$checkin = strip_tags($checkin);

$checkinPassword = filter_input(INPUT_POST, "txtCheckinPassword")??""; 
$checkinPassword = strip_tags($checkinPassword);

$newLocation = filter_input(INPUT_POST, "txtNewLocation")??""; 
$newLocation = strip_tags($newLocation);
$newPhone = filter_input(INPUT_POST, "txtNewPhone")??""; 
$newPhone = strip_tags($newPhone);
$newStatus = filter_input(INPUT_POST, "txtNewStatus")??""; 
$newStatus = strip_tags($newStatus);
$newOnline = filter_input(INPUT_POST, "cbNewOnline")??""; 
$newOnline = strip_tags($newOnline);

$lang = APP_DEF_LANG;
$lang1 = substr(strip_tags(filter_input(INPUT_GET, "hl")??""), 0, 5);
if ($lang1 !== PHP_STR) {
  $lang = $lang1;
}
$shortLang = getShortLang($lang);

$password = filter_input(INPUT_POST, "Password")??"";
$password = strip_tags($password);
if ($password !== PHP_STR) {	
  $hash = hash("sha256", $password . APP_SALT, false);

  foreach($aGroups as $keyg => $valg) {
    $aFriends = $valg;            
    $ii = 0; 
    foreach($aFriends as $keyf => $valf) {
      $ii++;
      $aFFields = $valf; 
      $name = $keyf;
      
      if ($hash === $aFFields['HASH']) {
        $groupName = $keyg; 
        $userName = $name;
        $defLocation = $aFFields['Location'];
        $defPhone = $aFFields['Phone'];
        $defStatus2 = $aFFields['defStatus'];
        $defOnline = $aFFields['online'];        
        break;
      }
    }
    
    if ($userName !== PHP_STR) {
      break;
    }  
  }  
 
  if ($userName === PHP_STR) {
    $password=PHP_STR;
    $curgroup=PHP_STR;
  }   
} 
if ($password !== PHP_STR || DEMO) {
  $CURRENT_VIEW = PUBLIC_VIEW;
} else {
  $CURRENT_VIEW = LOCKED_VIEW;
}

//echo("groupName=".$groupName."<br>");
//echo("userName=".$userName."<br>");
//echo("curgroup=".$curgroup."<br>");
//exit(1);
if ($curgroup===PHP_STR) {
  if ($groupName !== PHP_STR) {
    $curgroup=$groupName;
  }
}

function cleanPath($p) {
  $pattern = $p . DIRECTORY_SEPARATOR . "*";
  $paths = glob($pattern);
  foreach($paths as $path) {
    unlink($path);
  }
}

// CHECKIN
if (($password!==PHP_STR) && ($checkin!==PHP_STR) && ($checkinPassword === $password)) {
  $groupPath = APP_DATA_PATH . DIRECTORY_SEPARATOR . $groupName;
  $userPath = $groupPath . DIRECTORY_SEPARATOR . $userName;
  if (!is_readable($groupPath)) {
    mkdir($groupPath);
  }
  if (!is_readable($userPath)) {
    mkdir($userPath);
  }
  $locationPath = $userPath . DIRECTORY_SEPARATOR . "location"; 
  if (!is_readable($locationPath)) {
    mkdir($locationPath);
  }
  cleanPath($locationPath);
  $s = preg_replace("/[^\w\-\, ]/iu", "", $newLocation);
  touch($locationPath . DIRECTORY_SEPARATOR . $s);

  $phonePath = $userPath . DIRECTORY_SEPARATOR . "phone"; 
  if (!is_readable($phonePath)) {
    mkdir($phonePath);
  }
  cleanPath($phonePath);
  $s = preg_replace("/[^0-9\-\.\+ ]/iu", "", $newPhone);
  touch($phonePath . DIRECTORY_SEPARATOR . $s);

  $statusPath = $userPath . DIRECTORY_SEPARATOR . "status"; 
  if (!is_readable($statusPath)) {
    mkdir($statusPath);
  }
  cleanPath($statusPath);
  $s = preg_replace("/[^\w\-\_\.\,\: ]/iu", "", $newStatus);
  touch($statusPath . DIRECTORY_SEPARATOR . $s);

  $onlinePath = $userPath . DIRECTORY_SEPARATOR . "online"; 
  if (!is_readable($onlinePath)) {
    mkdir($onlinePath);
  }
  cleanPath($onlinePath);
  $s = preg_replace("/[^0-1]/iu", "", $newOnline);
  if ($s === PHP_STR) {
    $s = "1";
  }
  touch($onlinePath . DIRECTORY_SEPARATOR . $s);
}

function readdata($p) {
  $pattern = $p . DIRECTORY_SEPARATOR . "*";
  $paths = glob($pattern);
  if (count($paths)>0) {
    return basename($paths[0]);
  } else {
    return null;
  }
}

// READING INFO FROM DATA PATH

foreach($aGroups as $keyg => $valg) {
  //$aFriends = $valg;            
  $gname = $keyg; 
  //$ii = 0; 
  foreach($valg as $keyf => $valf) {
    //$ii++;
    //$aFFields = $valf; 
    $uname = $keyf;
    
    $groupPath = APP_DATA_PATH . DIRECTORY_SEPARATOR . $gname;
    $userPath = $groupPath . DIRECTORY_SEPARATOR . $uname;

    if (!is_readable($groupPath) || !is_readable($userPath)) {
      continue;
    }
    
    $locationPath = $userPath . DIRECTORY_SEPARATOR . "location"; 
    $valf["Location"] = readdata($locationPath)??$valf["Location"];
    $aGroups[$gname][$uname]["Location"] = $valf["Location"];
    //echo($valf["Location"]);
    $phonePath = $userPath . DIRECTORY_SEPARATOR . "phone"; 
    $valf["Phone"] = readdata($phonePath)??$valf["Phone"];
    $aGroups[$gname][$uname]["Phone"] = $valf["Phone"];
    //echo($valf["Phone"]);
    $statusPath = $userPath . DIRECTORY_SEPARATOR . "status"; 
    $valf["defStatus"] = readdata($statusPath)??$valf["defStatus"];
    $aGroups[$gname][$uname]["defStatus"] = $valf["defStatus"];
    //echo($valf["defStatus"]);
    $onlinePath = $userPath . DIRECTORY_SEPARATOR . "online"; 
    $valf["online"] = (int)readdata($onlinePath)??$valf["online"];
    $aGroups[$gname][$uname]["online"] = $valf["online"];
    //echo($valf["online"]);
    
  }
}  


?>


<!DOCTYPE html>
<html>
  <head>
    <title><?PHP echo(APP_TITLE);?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" href="/favicon.ico" />
    
    <script src="/js/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/js/sha.js" type="text/javascript"></script>
    <script src="/js/common.js" type="text/javascript"></script>
    
     <link href="/css/bootstrap.min.css" type="text/css" rel="stylesheet">
     <link href="/css/style.css" type="text/css" rel="stylesheet">
     
     <script>

    const male = 1;
    const female = 2;

    const defaultmale = "homo210.png";
    const defaultfemale = "homa200.png";

    // angle: 45; start: 10;
    const zoneNE = 1; //['Europe', 'UK', 'Russia', 'China', 'Middle-East', 'Indonesia']
    // angle:135; start: 100; 
    const zoneSE = 2; //['Africa', 'Australia'];
    // angle:-135; start: 210; 
    const zoneSW = 3; //['South America', 'Center America'];
    // angle:-45; start: 300;
    const zoneNW = 4; //['North America'];

    zoneNEbuddies = 0;
    zoneSEbuddies = 0;
    zoneSWbuddies = 0;
    zoneNWbuddies = 0;

    var nextFreeBuddy = 1;

    function setBuddyPosition(zone) {

      planetW = 568;
      planetH = 576;

      switch(zone) {
        case zoneNE:
          zoneNEbuddies++;
          //zoneNEbuddies++;
          deltaAng = (zoneNEbuddies - 1) * 47;
          deltaTopPos = (zoneNEbuddies -1) * 155;
          deltaLeftPos = (zoneNEbuddies -1) * 155;
          angle = 22 + (deltaAng);
          $("#buddy" + nextFreeBuddy).css("transform","rotate(" + angle + "deg)");
          buddyTop = (window.innerHeight/2)-(((planetH / 8.5)*5.83) - deltaTopPos);
          buddyLeft = (window.innerWidth/2)+(((planetW / 5.0)*0.6) + deltaLeftPos);
          break;
        case zoneSE:
          zoneSEbuddies++;
          //zoneSEbuddies++;
          deltaAng = (zoneSEbuddies - 1) * 47;
          deltaTopPos = (zoneSEbuddies -1) * 155;
          deltaLeftPos = (zoneSEbuddies -1) * 155;
          angle = 102 + (deltaAng);
          $("#buddy" + nextFreeBuddy).css("transform","rotate(" + angle + "deg)");
          buddyTop = (window.innerHeight/2)-(((planetH / 8.5)*0.03) - deltaTopPos);
          buddyLeft = (window.innerWidth/2)+(((planetW / 5.0)*2.0) - deltaLeftPos);           
          break;
        case zoneSW:  
          zoneSWbuddies++;
          //zoneSWbuddies++;
          deltaAng = (zoneSWbuddies - 1) * 47;
          deltaTopPos = (zoneSWbuddies -1) * 155;
          deltaLeftPos = (zoneSWbuddies -1) * 155;
          angle = 102 + (deltaAng);
          $("#buddy" + nextFreeBuddy).css("transform","rotate(-" + angle + "deg)");
          buddyTop = (window.innerHeight/2)-(((planetH / 8.5)*0.03) - deltaTopPos);
          buddyLeft = (window.innerWidth/2)-(((planetW / 5.0)*3.6) - deltaLeftPos);          
          break;
        case zoneNW:  
          zoneNWbuddies++;
          //zoneNWbuddies++;
          deltaAng = (zoneNWbuddies - 1) * 47;
          deltaTopPos = (zoneNWbuddies -1) * 155;
          deltaLeftPos = (zoneNWbuddies -1) * 155;
          angle = 22 + (deltaAng);
          $("#buddy" + nextFreeBuddy).css("transform","rotate(-" + angle + "deg)");
          buddyTop = (window.innerHeight/2)-(((planetH / 8.5)*5.83) - deltaTopPos);
          buddyLeft = (window.innerWidth/2)-(((planetW / 5.0)*2.5) + deltaLeftPos);          
          break;
      }
      
      $("#buddy" + nextFreeBuddy).css("position","absolute");
      $("#buddy" + nextFreeBuddy).css("top", buddyTop + "px");
      $("#buddy" + nextFreeBuddy).css("left", buddyLeft + "px");
      
    }

    function addMale(name, hairc, glasses, beard, zone) {
      
      //1=black hair
      //2=brown hair
      //3=blond hair
      
      if (hairc<0 || hairc>3) {
        alert("wrong param for male: hairc");
      }
      if (glasses<0 || glasses>1) {
        alert("wrong param for male: glasses");
      }
      if (beard<0 || beard>1) {
        alert("wrong param for male: beard");
      }
      t=""+hairc+glasses+beard;
      if (t!=="101" && t!=="210" && t!=="300") {
        t="210";
      }
      
      $("#buddy" + nextFreeBuddy).css("background-image", "url('/res/homo"+t+".png')");
      $("#buddy" + nextFreeBuddy).css("background-repeat", "no-repeat");
      $("#buddy" + nextFreeBuddy).css("background-size", "100% 100%");
      setBuddyPosition(zone);
      $("#buddy" + nextFreeBuddy + " .name").get(0).innerHTML=name;
      $("#buddy" + nextFreeBuddy).show();
      
      nextFreeBuddy++;
    }   

    function addFamale(name, hairc, glasses, beard, zone) {

      //1=black hair
      //2=brown hair
      //3=blond hair

      if (hairc<0 || hairc>3) {
        alert("wrong param for male: hairc");
      }
      if (glasses<0 || glasses>1) {
        alert("wrong param for male: glasses");
      }
      if (beard<0 || beard>0) {
        alert("wrong param for male: beard");
      }    
      t=""+hairc+glasses+beard;
      if (t!=="110" && t!=="200" && t!=="300") {
        t="200";
      }
      
      $("#buddy" + nextFreeBuddy).css("background-image", "url('/res/homa"+t+".png')");
      $("#buddy" + nextFreeBuddy).css("background-repeat", "no-repeat");
      $("#buddy" + nextFreeBuddy).css("background-size", "100% 100%");
      setBuddyPosition(zone);
      $("#buddy" + nextFreeBuddy + " .name").get(0).innerHTML=name;
      $("#buddy" + nextFreeBuddy).show();
      
      nextFreeBuddy++;
    }   
  
    function addBuddy(name, gender, hairc, glasses, beard, zone) {
      if (gender === "male") {
        addMale(name, hairc, glasses, beard, zone);
      } else {
        addFamale(name, hairc, glasses, beard, zone);
      }
    }   
    
  </script>

     <script>
               
    var burgerMenuVisible=false;
    function popupMenu(buddy, online) {
       if (!burgerMenuVisible) {
         menuRect = $("#buddy"+buddy+" .hal").get(0).getBoundingClientRect(); 
         $("#burgerMenu"+buddy).show();
         $("#burgerMenu"+buddy).css("z-index", "99999");
         $("#burgerMenu"+buddy).css("top", parseInt(menuRect.top-23));
         $("#burgerMenu"+buddy).css("left", parseInt(menuRect.left+40));
         if (online) {
           $("#muStatus"+buddy).css("color", "#55bb08");
           $("#muStatus"+buddy).css("font-weight", "900");
         } else {
           $("#muStatus"+buddy).css("color", "red");
           $("#muStatus"+buddy).css("font-weight", "900");
         }  
       } else {
         $(".burger-menu").hide();
         $(".burger-menu").css("z-index", "99992");
       }
       burgerMenuVisible=!burgerMenuVisible;
    } 

    function hideMenu() {
      $(".burger-menu").hide();
      burgerMenuVisible=false;
    }     

    function refresh() {
      $(".burger-menu").hide();
      frmSim.submit();
    }     

  </script>
  
     <script>
               
    function selGroup(group) {
      <?PHP if ($CURRENT_VIEW === PUBLIC_VIEW): ?>
      $("#_group").val(group);
      frmSim.submit();
      <?PHP Endif; ?>
    }           
    
    var buddiesVis = [1,1,1,1,1,1,1,1];
    function toggleBuddie(buddie) {
      if (buddiesVis[buddie]===1) {
        $("#buddy"+buddie).hide();
        buddiesVis[buddie]=0;
        $("#buddysel"+buddie).css("background-color", "#FFFFFF");
      } else {
        $("#buddy"+buddie).show();
        buddiesVis[buddie]=1;
        $("#buddysel"+buddie).css("background-color", "lightblue");
      }  
    }           

    function checkin() {
      hideMenu();     
      $('#modalButton1').click();      
    }  

    function saveCheckin() {
      $("#_checkin").val("1");
      frmSim.submit();
    }  

  </script>
  
  </head>
  <body>

    <div class="header" style="margin-top:18px;margin-bottom:18px;">
         <a href="http://sim.pli.city" target="_self" style="color:#000000; text-decoration: none;">&nbsp;<img src="/res/planet.png" style="width:22px;">&nbsp;Sim.pli.city</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://github.com/par7133/Simplicity" style="color:#000000;"><span style="color:#119fe2">on</span> github</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:posta@elettronica.lol" style="color:#000000;"><span style="color:#119fe2">for</span> feedback</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tel:+39-331-4029415" style="font-size:13px;background-color:#15c60b;border:2px solid #15c60b;color:#000000;height:27px;text-decoration:none;">&nbsp;&nbsp;get support&nbsp;&nbsp;</a>
   </div>
    
    <form id="frmSim" action="." method="POST" target="_self">   
    
    <div id="content">
    
         <?PHP if ($CURRENT_VIEW === PUBLIC_VIEW): ?>
      
          <div id="groups" >
               <?PHP 
        foreach($aGroups as $keyg => $valg) {
          if ($keyg === $curgroup) {
            echo("<div class='group active-group'>".$keyg."</div>\n");
            $aFriends = $valg;            
            $ii = 0; 
            foreach($aFriends as $keyf => $valf) {
              $ii++;
              echo("<div  id='buddysel".$ii."' class='buddysel' onclick=toggleBuddie(".$ii.")>".$keyf."</div>\n");
            }
          } else {
            echo("<div class='group' onclick=selGroup('".$keyg."')>".$keyg."</div>\n");
          }
        }  
      ?>    
          </div>
      
         <?PHP EndIf; ?>
      
         <?PHP if ($CURRENT_VIEW === LOCKED_VIEW || ($curgroup==PHP_STR && DEMO)): ?>
         <?PHP echo("<div id='emptyPlanet'>Oh, a brand new planet !&nbsp;" . ((DEMO)?"<span class='demo'>[ DEMO ]</span>":"") . "</div>"); ?> 
         <?PHP Endif; ?>
      
          <div id="planet">&nbsp;</div>
          
                <?PHP 
        foreach($aGroups as $keyg => $valg) {
          if ($keyg === $curgroup) {
            $aFriends = $valg;            
            $gname = $keyg;
            $ii = 0; 
            foreach($aFriends as $keyf => $valf) {
              $ii++;
              $aFFields = $valf; 
              $name = $keyf;
              $gender = $aFFields['gender'];
              $location = $aFFields['Location'];
              $desc = $aFFields['Desc'];
              $online = $aFFields['online'];
              //echo("$name;online$ii=$online<br>");
              $defStatus = $aFFields['defStatus'];
              //echo("defStatus$ii=$defStatus<br>");
              $phone = $aFFields['Phone'];
              $homomm = $aFFields['Homomm'];
              $blog = $aFFields['Blog'];
              $www = $aFFields['www'];
              $zone = $aFFields['zone'];
              ?>
          
           <div id="buddy<?PHP echo($ii);?>" class="buddy"><div class="name">&nbsp;</div>&nbsp;<div class="hal" onclick="popupMenu(<?PHP echo($ii);?>,<?PHP echo($online);?>)"><img src="<?PHP echo(($online===1?"/res/hall.png":"/res/hal.png"));?>"></div></div>
          
           <div id="burgerMenu<?PHP echo($ii);?>" class="burger-menu" style="display: none; z-index:99997; border: 0px solid yellow;">
              <div id="muName<?PHP echo($ii);?>" class="vmuBurgerMenu"><?PHP echo($name); ?>&nbsp;[<?PHP echo($location); ?>]&nbsp;</div>
              <div id="muDesc<?PHP echo($ii);?>" class="vmuBurgerMenu"><?PHP echo($desc); ?>&nbsp;</div>
              <div id="muStatus<?PHP echo($ii);?>" class="vmuBurgerMenu"><?PHP echo($defStatus); ?>&nbsp;</div>
              <hr style="border:1px solid #003333;">
              <?PHP if (($gname===$groupName && $name===$userName) || DEMO):?>
              <div id="muCheckIn<?PHP echo($ii);?>" class="vmuBurgerMenu" onclick="checkin('<?PHP echo($gname);?>','<?PHP echo($name);?>')">Check-in&nbsp;</div>
               <hr style="border:1px solid #003333;">
              <?PHP EndIf; ?> 
              <div id="muCall<?PHP echo($ii);?>" class="vmuBurgerMenu"><a href="call:<?PHP echo($phone); ?>">call: <?PHP echo($phone); ?>&nbsp;</div>
              <div id="muHomomm<?PHP echo($ii);?>" class="vmuBurgerMenu"><a href="<?PHP echo($homomm); ?>">Homomm</a></div>
              <div id="muBlog<?PHP echo($ii);?>" class="vmuBurgerMenu"><a href="<?PHP echo($blog); ?>">Blog</a>&nbsp;</div>
              <div id="muWWW<?PHP echo($ii);?>" class="vmuBurgerMenu"><a href="<?PHP echo($www); ?>">www</a>&nbsp;</div>
              <hr style="border:1px solid #003333;">
              <div id="muReset<?PHP echo($ii);?>" class="vmuBurgerMenu" onclick="refresh();">Refresh&nbsp;</div>
          </div>
          
          <?PHP
            }
            break;
          }
        }  
      ?>              
       
                 <button id="modalButton1" type="button" class="btn btn-primary" style="display:none;" data-toggle="modal" data-target="#modal1">Button #1</button>

                    <div class="modal" tabindex="-1" role="dialog" id="modal1">
                      <div class="modal-dialog modal-sm my-modal-dialog" role="document">
                        <div class="modal-content my-modal-content">
                           <div style="position:absolute; top:10px; padding:50px;">
                             <table style="width:100%">
                              <tr>  
                                <td class="checkin-cell">Location:&nbsp;</td><td width="450px"><input class="checkin-field" id="txtNewLocation" name="txtNewLocation" type="text" value="<?PHP echo($defLocation);?>"></td>
                             </tr>
                             <tr>  
                                <td class="checkin-cell">Phone:&nbsp;</td><td width="450px"><input class="checkin-field" id="txtNewPhone" name="txtNewPhone" type="text" value="<?PHP echo($defPhone);?>"></td>
                             </tr>
                             <tr>
                                <td class="checkin-cell">Status&nbsp;</td><td width="450px"><input class="checkin-field" id="txtNewStatus" name="txtNewStatus" type="text" value="<?PHP echo($defStatus2);?>"></td>
                              </tr>
                             <tr>
                                <td class="checkin-cell">Online?&nbsp;</td><td width="450px">
                                  <select id="cbNewOnline" name="cbNewOnline">
                                    <option value="0">offline</option>
                                    <option value="1" selected>online</option>
                                  </select>  
                              </tr>
                              <tr>
                                <td class="checkin-cell">Password:&nbsp;</td><td width="450px"><input class="checkin-field" id="txtCheckinPassword" name="txtCheckinPassword" type="password" value=""></td>
                              </tr>
                              <tr>
                                <td class="checkin-cell" colspan="2"><br><br></td>
                              </tr>
                              <tr>
                                <td class="checkin-cell" colspan="2"><input id="butSave" name="butSave" type="button" onclick="saveCheckin()" value="Save">&nbsp;&nbsp;<input id="butReset" name="butReset" type="reset" value="Reset"></td>
                              </tr>
                             </table>
                            </div> 
                        </div>
                        <div class="modal-toolbox" style="float:left;">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>   
                        </div>   
                      </div>  
                    </div> 
           
    </div>      
   
      <input id="_group" name="_group" type="hidden" value="<?PHP echo($curgroup)?>">  
      <input id="_checkin" name="_checkin" type="hidden" value="">  

     <div id="passworddisplay">
       <br>  
        &nbsp;&nbsp;<input type="password" id="Password" name="Password" placeholder="password" value="<?php echo($password);?>" autocomplete="off">&nbsp;<input type="button" onclick="passwordSubmit()" value="<?PHP echo(getResource0("Go", $lang));?>" style="text-align:left;width:25%;color:#000000;"><br>
        &nbsp;&nbsp;<input type="text" id="Salt" placeholder="salt" autocomplete="off"><br>
        <div style="text-align:center;">
           <a id="hashMe" href="#" onclick="showEncodedPassword();"><?PHP echo(getResource0("Hash Me", $lang));?>!</a>
        </div>
     </div> 
      
   </form>   
      
  <div id="footerCont">&nbsp;</div>
  <div id="footer" style="float:right; width:520px; white-space: nowrap;">
      <span style="background:#FFFFFF; opacity:0.7;">&nbsp;&nbsp;A <a href="http://5mode.com" class="aaa">5 Mode</a> project and <a href="http://demo.5mode.com" class="aaa">WYSIWYG</a> system. <?PHP echo(getResource0("Some rights reserved", $lang));?>.</span>
  </div>
    
    <script>    
    var ito;
    window.addEventListener("load", function() {
      $("#planet").css("height", window.innerHeight); 

      <?PHP 
        foreach($aGroups as $keyg => $valg) {
          if ($keyg === $curgroup) {
            $aFriends = $valg;            
            $ii = 0; 
            foreach($aFriends as $keyf => $valf) {
              $aFFields = $valf; 
              $name = $keyf;
              $gender = $aFFields['gender'];
              $hairc = $aFFields['hairc'];
              $glasses = $aFFields['glasses'];
              $beard = $aFFields['beard'];
              $zone = $aFFields['zone'];
              echo("addBuddy('".$name."', '".$gender."',".$hairc.",".$glasses.",".$beard.",".$zone.");");
              $ii++;
            }
            break;
          } 
        }  
      ?>    

    });

    function reloadme() {
      frmSim.submit();
    }    
  
    window.addEventListener("resize", function() {
      
      $("#content").hide();
      
      ito = setTimeout("reloadme()","1500");
      
    });
 </script>    
    
  <script>
  function setFooterPos() {
    if (document.getElementById("footerCont")) {
      tollerance = 16;
      $("#footerCont").css("top", parseInt( window.innerHeight - $("#footerCont").height() - tollerance ) + "px");
      $("#footer").css("left", parseInt( window.innerWidth - $("#footer").width() - tollerance ) + "px");
      $("#footer").css("top", parseInt( window.innerHeight - $("#footer").height() - tollerance ) + "px");
    }
  }

  window.addEventListener("load", function() {
    setTimeout("setFooterPos()", 1000);
  });  
  
  window.addEventListener("resize", function() {
    setTimeout("setFooterPos()", 1000);
  });  
 </script>
 
  <script src="/static/js/home-js.php?hl=<?PHP echo($lang);?>&cv=<?PHP echo($CURRENT_VIEW);?>" type="text/javascript"></script>
 
  <!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(83812639, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/83812639" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
  
</body>
</html>
