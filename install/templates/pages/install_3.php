<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

  require('../includes/database_tables.php');

  wh_db_connect(trim($_POST['DB_SERVER']), trim($_POST['DB_SERVER_USERNAME']), trim($_POST['DB_SERVER_PASSWORD']));
  wh_db_select_db(trim($_POST['DB_DATABASE']));

  wh_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_NAME']) . '" where configuration_key = "STORE_NAME"');
  wh_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_NAME']) . '" where configuration_key = "STORE_OWNER"');
  wh_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where configuration_key = "STORE_OWNER_EMAIL_ADDRESS"');

  if (!empty($_POST['CFG_STORE_OWNER_NAME']) && !empty($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS'])) {
    wh_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "\"' . trim($_POST['CFG_STORE_OWNER_NAME']) . '\" <' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '>" where configuration_key = "EMAIL_FROM"');
  } else {
    wh_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where configuration_key = "EMAIL_FROM"');
  }

  $check_query = wh_db_query('select user_name from ' . TABLE_ADMINISTRATORS . ' where user_name = "' . trim($_POST['CFG_ADMINISTRATOR_USERNAME']) . '"');

  if (wh_db_num_rows($check_query)) {
    wh_db_query('update ' . TABLE_ADMINISTRATORS . ' set user_password = "' . wh_encrypt_string(trim($_POST['CFG_ADMINISTRATOR_PASSWORD'])) . '" where user_name = "' . trim($_POST['CFG_ADMINISTRATOR_USERNAME']) . '"');
  } else {
    wh_db_query('insert into ' . TABLE_ADMINISTRATORS . ' (user_name, user_password) values ("' . trim($_POST['CFG_ADMINISTRATOR_USERNAME']) . '", "' . wh_encrypt_string(trim($_POST['CFG_ADMINISTRATOR_PASSWORD'])) . '")');
  }

  wh_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where configuration_key = "MODULE_PAYMENT_PAYPAL_EXPRESS_SELLER_ACCOUNT"');
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
    <h3>Step 4: Finished!</h3>

    <div class="infoPaneContents">
      <p>Congratulations on installing and configuring Sports Cubed!</p>
      <p>We wish you all the best and welcome you to join and participate in our community.</p>
      <p align="right">- The Sports Cubed Team</p>
    </div>
  </div>

  <div class="contentPane">
    <h2>Finished!</h2>

<?php
  $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '\\') && (substr($dir_fs_document_root, -1) != '/')) {
    if (strrpos($dir_fs_document_root, '\\') !== false) {
      $dir_fs_document_root .= '\\';
    } else {
      $dir_fs_document_root .= '/';
    }
  }

  $admin_folder = 'admin';
  if (isset($_POST['CFG_ADMIN_DIRECTORY']) && !empty($_POST['CFG_ADMIN_DIRECTORY']) && wh_is_writable($dir_fs_document_root) && wh_is_writable($dir_fs_document_root . 'admin')) {
    $admin_folder = preg_replace('/[^a-zA-Z0-9]/', '', trim($_POST['CFG_ADMIN_DIRECTORY']));

    if (empty($admin_folder)) {
      $admin_folder = 'admin';
    }
  }

  $file_contents = '<?php' . "\n" .
                   '  define(\'HTTP_SERVER\', \'' . $http_server . '\');' . "\n" .
                   '  define(\'HTTPS_SERVER\', \'' . $http_server . '\');' . "\n" .
                   '  define(\'ENABLE_SSL\', false);' . "\n" .
                   '  define(\'HTTP_COOKIE_DOMAIN\', \'\');' . "\n" .
                   '  define(\'HTTPS_COOKIE_DOMAIN\', \'\');' . "\n" .
                   '  define(\'HTTP_COOKIE_PATH\', \'' . $http_catalog . '\');' . "\n" .
                   '  define(\'HTTPS_COOKIE_PATH\', \'' . $http_catalog . '\');' . "\n" .
                   '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
                   '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
                   '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                   '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                   '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                   '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                   '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                   '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                   '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n\n" .
                   '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" .
                   '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" .
                   '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
                   '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n\n" .
                   '  define(\'DB_SERVER\', \'' . trim($_POST['DB_SERVER']) . '\');' . "\n" .
                   '  define(\'DB_SERVER_USERNAME\', \'' . trim($_POST['DB_SERVER_USERNAME']) . '\');' . "\n" .
                   '  define(\'DB_SERVER_PASSWORD\', \'' . trim($_POST['DB_SERVER_PASSWORD']) . '\');' . "\n" .
                   '  define(\'DB_DATABASE\', \'' . trim($_POST['DB_DATABASE']) . '\');' . "\n" .
                   '  define(\'USE_PCONNECT\', \'false\');' . "\n";

  if (isset($_POST['CFG_TIME_ZONE'])) {
    $file_contents .= '  define(\'CFG_TIME_ZONE\', \'' . trim($_POST['CFG_TIME_ZONE']) . '\');' . "\n";
  }

  $file_contents .= '?>';

  $fp = fopen($dir_fs_document_root . 'includes/configure.php', 'w');
  fputs($fp, $file_contents);
  fclose($fp);

  @chmod($dir_fs_document_root . 'includes/configure.php', 0644);

  if ($admin_folder != 'admin') {
    @rename($dir_fs_document_root . 'admin', $dir_fs_document_root . $admin_folder);
  }
?>

    <p>The installation and configuration was successful!</p>

    <br />

    <table border="0" width="99%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" width="50%"><a href="<?php echo $http_server . $http_catalog . 'index.php'; ?>" target="_blank"><img src="images/button_catalog.gif" border="0" alt="Catalog" /></a></td>
        <td align="center" width="50%"><a href="<?php echo $http_server . $http_catalog . $admin_folder . '/index.php'; ?>" target="_blank"><img src="images/button_administration_tool.gif" border="0" alt="Administration Tool" /></a></td>
      </tr>
    </table>

    <br />

    <h3>Post-Installation Notes</h3>

    <p>It is recommended to follow the following post-installation steps to secure your Sports Cubed installation:</p>

    <ol>
      <li>Delete the <?php echo $dir_fs_document_root . 'install'; ?> directory.</li>

<?php
  if ($admin_folder == 'admin') {
?>

      <li>Rename the Administration Tool directory located at <?php echo $dir_fs_document_root . 'admin'; ?>.</li>

<?php
  }

  if (file_exists($dir_fs_document_root . 'includes/configure.php') && wh_is_writable($dir_fs_document_root . 'includes/configure.php')) {
?>

      <li>Set the permissions on <?php echo $dir_fs_document_root . 'includes/configure.php'; ?> to 644 (or 444 if this file is still writable).</li>

<?php
  }

  if (file_exists($dir_fs_document_root .  $admin_folder . '/includes/configure.php') && wh_is_writable($dir_fs_document_root . $admin_folder . '/includes/configure.php')) {
?>

      <li>Set the permissions on <?php echo $dir_fs_document_root . $admin_folder . '/includes/configure.php'; ?> to 644 (or 444 if this file is still writable).</li>

<?php
  }
?>

      <li>Review the directory permissions on the Administration Tool -> Tools -> Security Directory Permissions page.</li>
      <li>The Administration Tool should be further protected using htaccess/htpasswd and can be set-up within the Configuration -> Administrators page.</li>
    </ol>
  </div>
</div>
