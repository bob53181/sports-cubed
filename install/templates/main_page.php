<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>osCommerce, Open Source E-Commerce Solutions</title>

<meta name="robots" content="noindex,nofollow">

<link rel="stylesheet" type="text/css" href="templates/main_page/stylesheet.css">

<link rel="stylesheet" type="text/css" href="ext/niftycorners/niftyCorners.css">
<script type="text/javascript" src="ext/niftycorners/nifty.js"></script>

</head>

<body>

<div id="pageHeader">
  <div>
    <div style="float: right; padding-top: 40px; padding-right: 15px; color: #000000; font-weight: bold;"><a href="http://www.oscommerce.com" target="_blank">osCommerce Website</a> &nbsp;|&nbsp; <a href="http://www.oscommerce.com/support" target="_blank">Support</a> &nbsp;|&nbsp; <a href="http://www.oscommerce.info" target="_blank">Documentation</a></div>

    <a href="index.php"><img src="images/logo.png" border="0" title="osCommerce Online Merchant" style="margin: 5px;" /></a>
  </div>
</div>

<script type="text/javascript">
<!--
  if (NiftyCheck()) {
    Rounded("div#pageHeader", "all", "#FFFFFF", "#f7f7f5", "smooth border #b3b6b0");
  }
//-->
</script>

<div id="pageContent">
<?php require('templates/pages/' . $page_contents); ?>
</div>

</body>

</html>