<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

// set default timezone if none exists (PHP 5.3 throws an E_WARNING)
  if ((strlen(ini_get('date.timezone')) < 1) && function_exists('date_default_timezone_set')) {
    date_default_timezone_set(@date_default_timezone_get());
  }
?>