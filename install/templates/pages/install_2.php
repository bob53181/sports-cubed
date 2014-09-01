<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  $www_location = 'http://' . $_SERVER['HTTP_HOST'];

  if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
    $www_location .= $_SERVER['REQUEST_URI'];
  } else {
    $www_location .= $_SERVER['SCRIPT_FILENAME'];
  }

  $www_location = substr($www_location, 0, strpos($www_location, 'install'));

  $dir_fs_www_root = wh_realpath(dirname(__FILE__) . '/../../../') . '/';
?>

<div class="mainBlock">
  <div class="stepsBox">
    <ol>
      <li>Database Server</li>
      <li>Settings</li>
      <li style="font-weight: bold;">Finished!</li>
    </ol>
  </div>

  <h1>New Installation</h1>

  <p>This web-based installation routine will correctly setup and configure Sports Cubed to run on this server.</p>
  <p>Please follow the on-screen instructions that will take you through the database server and Sports Cubed configuration options. If help is needed at any stage, please consult the documentation or seek help at the community support forums or mailing list.</p>
</div>

<div class="contentBlock">
  <div class="infoPane">
    <h3>Step 2: Web Server</h3>

    <div class="infoPaneContents">
      <p>The web server takes care of serving the sports clubs information.</p>
    </div>
  </div>

  <div class="contentPane">
    <h2>Web Server</h2>

    <form name="install" id="installForm" action="install.php?step=3" method="post">

    <table border="0" width="99%" cellspacing="0" cellpadding="5" class="inputForm">
      <tr>
        <td class="inputField"><?php echo 'Database directory<br />' . wh_draw_input_field('DIR_WS_DATABASE', '../database/', 'class="text"'); ?></td>
        <td class="inputDescription">The directory for storing xml databases.</td>
      </tr>
      <tr>
        <td class="inputField"><?php echo 'Time Zone<br />' . wh_draw_time_zone_select_menu('CFG_TIME_ZONE'); ?></td>
        <td class="inputDescription">The time zone to base the date and time on.</td>
      </tr>
    </table>

    <p align="right"><input type="image" src="images/button_continue.gif" border="0" alt="Continue" id="inputButton" />&nbsp;&nbsp;<a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel" /></a></p>

<?php
  reset($_POST);
  while (list($key, $value) = each($_POST)) {
    if (($key != 'x') && ($key != 'y')) {
      if (is_array($value)) {
        for ($i=0, $n=sizeof($value); $i<$n; $i++) {
          echo wh_draw_hidden_field($key . '[]', $value[$i]);
        }
      } else {
        echo wh_draw_hidden_field($key, $value);
      }
    }
  }
?>

    </form>
  </div>
</div>