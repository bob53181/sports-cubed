<?php

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . 'local/parser.php');

  $xml = loadXML ( URL_XML_COUNCIL );
  $query = build_query_council_edinburgh ( 'club' );
//  $query = buildQuery ( 'sport' ) . buildQuery ( 'time' );
  delete_clubs ();
  process_clubs_council_edinburgh ($xml, $query);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>