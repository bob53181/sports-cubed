<?php
// doxygen fix
// @cond DUMMY
  if ( ! function_exists ( 'mysqli_fetch_all' ) )
  {
    function mysqli_fetch_all ( $db_query, $resulttype = MYSQLI_ASSOC )
    {
      $res = array();
      while ( $tmp = mysqli_fetch_array ($db_query, $resulttype) ) {
        $res[] = $tmp;
      }
      return $res;
    }
  }
// @endcond

  function wh_db_multi_query ($query, $link = 'db_link') {
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (defined('STORE_PAGE_PARSE_TIME_LOG') &&
          (STORE_PAGE_PARSE_TIME_LOG != '')) {
        error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      } else {
        error_log('QUERY ' . $query . "\n", 0);
      }
    }

    $result = mysqli_multi_query($$link, $query) or wh_db_error($query, mysqli_errno($$link), mysqli_error($$link));

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      $result_error = mysqli_error();
      if (defined('STORE_PAGE_PARSE_TIME_LOG') &&
          (STORE_PAGE_PARSE_TIME_LOG != '')) {
        error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      } else {
        error_log('RESULT ' . $result . ' ' . $result_error . "\n", 0);
      }
    }

    return $result;
  }

  function wh_db_fetch_array_custom($db_query) {
    if ( ! wh_db_num_rows($db_query) )
      return false;
    return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }

  function wh_db_fetch_assoc_custom($db_query) {
    if ( ! wh_db_num_rows($db_query) )
      return false;
    return mysqli_fetch_assoc($db_query);
  }

  function wh_db_fetch_row_custom($db_query) {
    if ( ! wh_db_num_rows($db_query) )
      return false;
    return mysqli_fetch_row($db_query);
  }

  function wh_db_fetch_object_custom($db_query) {
    if ( ! wh_db_num_rows($db_query) )
      return false;
    return mysqli_fetch_object($db_query);
  }

  function wh_db_fetch_all_custom($db_query, $resulttype = MYSQLI_ASSOC) {
    if ( ! wh_db_num_rows($db_query) )
      return false;
    return mysqli_fetch_all($db_query, $resulttype);
  }

  function echoQuery ( $query )
  {
    echo $query . '<br />' . PHP_EOL;
  }

  function wh_db_post_input_check ( $arg )
  {
    return isset ( $_POST [$arg] )
      && wh_not_null ( $_POST [$arg] );
  }

  function wh_db_post_input_check_string ( $arg )
  {
    return isset ( $_POST [$arg] )
      && $_POST [$arg] != null
      && is_string  ( $_POST [$arg] )
      && strlen ( $_POST [$arg] );
  }

  function wh_db_post_input_prepare ( $arg )
  {
    return ( isset ( $_POST [$arg] ) && wh_not_null ( $_POST [$arg] ) ) ?
        wh_db_prepare_input ( $_POST [$arg] ) : null;
  }

  function wh_db_post_input_string ( $arg )
  {
    return (isset ( $_POST [$arg] )
      && wh_not_null ( $_POST [$arg] )
      && is_string  ( $_POST [$arg] ) ?
        wh_db_prepare_input ( $_POST [$arg] ) : null);
  }

  function wh_db_post_input_prepare_array ( $arg )
  {
    return (isset ( $_POST [$arg] )
      && wh_not_null ( $_POST [$arg] )
      && is_array ( $_POST [$arg] ) ?
        wh_db_prepare_input ( $_POST [$arg] ) : array ());
  }

  function wh_db_get_input_check ( $arg )
  {
    return isset ( $_GET [$arg] )
      && wh_not_null ( $_GET [$arg] );
  }

  function wh_db_get_input_check_string ( $arg )
  {
    return isset ( $_GET [$arg] )
      && $_GET [$arg] != null
      && strlen ( $_GET [$arg] );
  }

  function wh_db_get_input_prepare ( $arg )
  {
    return (isset ( $_GET [$arg] )
      && wh_not_null ( $_GET [$arg] ) ?
        wh_db_prepare_input ( $_GET [$arg] ) : null);
  }

  function wh_db_get_input_string ( $arg )
  {
    return (isset ( $_GET [$arg] )
      && wh_not_null ( $_GET [$arg] )
      && is_string  ( $_GET [$arg] ) ?
        wh_db_prepare_input ( $_GET [$arg] ) : null);
  }

  function wh_db_get_input_array ( $arg )
  {
    return (isset ( $_GET [$arg] )
      && wh_not_null ( $_GET [$arg] )
      && is_array ( $_GET [$arg] ) ?
        wh_db_prepare_input ( $_GET [$arg] ) : array ());
  }

  /**
   * If value is null, replaces it with 'null'
   * @param arg string to test
   * @return input argument or 'null'
   */
  function wh_db_prepare_null ( $arg )
  {
    if ( wh_null ($arg) )
      return 'null';
    return $arg;
  }

  /**
   * Check the length of input text for the database limit
   * @param arg string to test
   * @param length_limit maximum length of the string
   * @param arg_name name of the input that will be shown in the error message
   * @return boolean true or false
   */
  function wh_db_limit_length ( $arg, $length_limit, $arg_name )
  {
    if ( wh_not_null ( $arg ) && strlen ( $arg ) > $length_limit )
    {
      wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">'
                . $arg_name . ' is too long &mdash; over '
                . $length_limit . ' symbols</strong>' );
      return false;
    }
    return true;
  }

  /**
   * Replaces a value in array, using a reference to the array
   */
  function array_replace_value(&$ar, $value, $replacement)
  {
    if ( ( $key = array_search($value, $ar) ) !== false )
    {
      $ar[$key] = $replacement;
    }
  }

  function filterDays ( $days )
  {
#   var_dump ( $days );
#   die ();
    array_replace_value ( $days, 'monday', 1 );
    array_replace_value ( $days, 'tuesday', 2 );
    array_replace_value ( $days, 'wednesday', 3 );
    array_replace_value ( $days, 'thursday', 4 );
    array_replace_value ( $days, 'friday', 5 );
    array_replace_value ( $days, 'saturday', 6 );
    array_replace_value ( $days, 'sunday', 7 );
    array_replace_value ( $days, 'everyday', 8 );
#   array_replace_value ( $days, 'whole week', 8 );
#   array_replace_value ( $days, 'workweek', 9 );
#   array_replace_value ( $days, 'weekend', 10 );
#   var_dump ( $days );
    if ( in_array ( 8, $days ) ) {
      return range (1, 10);
    }
    $days = array_intersect ( $days, range(1, 8) );
    if ( in_array (1, $days) || in_array (2, $days) ||
         in_array (3, $days) || in_array (4, $days) ||
         in_array (5, $days) )
    {
      array_push ( $days, 9 );
    }
    if ( in_array (6, $days) || in_array (7, $days) )
    {
      array_push ( $days, 10 );
    }
    array_push ( $days, 8 );
    // Not needed - the input doesn't currently contain 9 or 10
#   if ( in_array ( 10, $days ) ) {
#     array_push ( $days, 6, 7 );
#   }
    // If all days are selected, simply return range (1, 10)
    // This means there is no need for days check in queries
#   $real_days = array_intersect ( $days, range(1, 7) );
#   if ( $real_days == range (1, 7) ) {
#     return range (1, 10);
#   }
    $days = array_unique ( $days, SORT_NUMERIC );
    return $days;
  }

  function getClubs ( )
  {
    return getClubsOrderByName ( );
  }

  function getClubsOrderById ( )
  {
    return wh_db_query ('select * from ' . TABLE_CLUBS_PRODUCTION .
                        ' order by id');
  }

  function getClubsOrderByName ( )
  {
    return wh_db_query ('select * from ' . TABLE_CLUBS_PRODUCTION .
                        ' order by name');
  }

  function getClubById ( $id )
  {
    return wh_db_query ('select * from ' . TABLE_CLUBS_PRODUCTION .
                        " where id = '{$id}'");
  }

  function getClubByName ( $name )
  {
    return wh_db_query ('select * from ' . TABLE_CLUBS_PRODUCTION .
                        " where name = '{$name}'");
  }

  function getClubScheduleTime ($id)
  {
    return wh_db_query ('select day_id, opening_time, closing_time' .
                        ' from ' . TABLE_CLUB_SCHEDULE_PRODUCTION .
                        " where club_id = '{$id}'");
  }

  function getClubSchedulePrice ($id)
  {
    return wh_db_query ('select day_id, price_member, price_nonmember' .
                        ' from ' . TABLE_CLUB_SCHEDULE_PRODUCTION .
                        " where club_id = '{$id}'");
  }

  /**
   * @param $table entity table - sports or facilities
   * @param $data array with entities - sports or facilities
   * @return query result
   */
  function getClubsBySportsId ( $table, $data )
  {
    if ( $table == 'sports' )
    {
      $entity_table = TABLE_SPORTS;
      $junction_table = TABLE_CLUBOSPORT_PRODUCTION;
      $entity_id = 'sport_id';
    }
    else if ( $table == 'facilities' )
    {
      $entity_table = TABLE_FACILITIES;
      $junction_table = TABLE_CLUB_FACILITIES_PRODUCTION;
      $entity_id = 'facility_id';
    } else {
      wh_error ('Check your SQL queries');
    }
    $query = 'select ' . TABLE_CLUBS_PRODUCTION . '.id as id';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.name as name';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.latitude';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.longtitude';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.website';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.email';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.phone';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.comment';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.opening_time';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.closing_time';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.price_member';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.price_nonmember';
    $query .= ' from ' . TABLE_CLUBS_PRODUCTION . '';
    $query .= ", {$junction_table} as " . $junction_table;
    $query .= ' where';
    $query .= ' ' . TABLE_CLUBS_PRODUCTION . ".id = {$junction_table}.club_id";
    $query .= ' and ';
    $query .= $junction_table . '.' . $entity_id . ' in (';
    $data = array_values ( $data );
    foreach ( $data as $entity )
    {
      $query .= "'{$entity}', ";
    }
    $query = substr ($query, 0, -2);
    $query .= ')';
    $query .= ' group by id';
#   $query .= ' order by id';
#   echoQuery ($query);
    return wh_db_query ( $query );
  }

  /**
   * @param $table entity table - sports or facilities
   * @param $data array with entities - sports or facilities
   * @return query result
   */
  function getClubsBySports ( $table, $data )
  {
    if ( $table == 'sports' )
    {
      $entity_table = TABLE_SPORTS;
      $junction_table = TABLE_CLUBOSPORT_PRODUCTION;
      $entity_id = 'sport_id';
    }
    else if ( $table == 'facilities' )
    {
      $entity_table = TABLE_FACILITIES;
      $junction_table = TABLE_CLUB_FACILITIES_PRODUCTION;
      $entity_id = 'facility_id';
    } else {
      wh_error ('Check your SQL queries');
    }
    $query = 'select ' . TABLE_CLUBS_PRODUCTION . '.id as id';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.name as name';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.latitude';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.longtitude';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.website';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.email';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.phone';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.comment';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.opening_time';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.closing_time';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.price_member';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.price_nonmember';
    $query .= ' from ' . TABLE_CLUBS_PRODUCTION . '';
    $query .= ', ' . $junction_table;
    $query .= ', ' . $entity_table;
    $query .= ' where';
    $query .= ' ' . TABLE_CLUBS_PRODUCTION . ".id = {$junction_table}.club_id";
    $query .= " and {$entity_table}.id = {$junction_table}.{$entity_id}";
    $query .= ' and ';
    $query .= $entity_table . '.name in (';
    $data = array_values ( $data );
    foreach ( $data as $entity )
    {
      $query .= "'{$entity}', ";
    }
    $query = substr ($query, 0, -2);
    $query .= ')';
    $query .= ' group by id';
#   $query .= ' order by id';
#   echoQuery ($query);
    return wh_db_query ( $query );
  }

  /**
   * @param $table entity table - sports or facilities
   * @param $data array with entities - sports or facilities
   * @param $days array with selected days
   * @param $time array with time schedules for selected days
   * @param $price array with prices for selected days
   * @return query result
   */
  function getClubsBySportsDaysTimePrice ( $table, $data, $days, $time, $price )
  {
    if ( ! is_array ( $days ) || $days == [] ) {
      $days = range (1, 10);
      $days_selected = false;
    }
    else {
      $days_selected = true;
    }
    if ( ! is_array ( $time ) || count ( $time ) !== 2 ||
          is_null ($time ['member']) || is_null ($time ['nonmember']) ) {
      $time = null;
    }
    if ( ! is_array ( $price ) || count ( $price ) === 0 ||
        ((! isset ($price ['member'])    || is_null ($price ['member'])) &&
         (! isset ($price ['nonmember']) || is_null ($price ['nonmember']))) ) {
      $price = [];
    }
    if ( $table == 'sports' )
    {
      $entity_table = TABLE_SPORTS;
      $junction_table = TABLE_CLUBOSPORT_PRODUCTION;
      $entity_id = 'sport_id';
    }
    else if ( $table == 'facilities' )
    {
      $entity_table = TABLE_FACILITIES;
      $junction_table = TABLE_CLUB_FACILITIES_PRODUCTION;
      $entity_id = 'facility_id';
    } else {
      wh_error ('Check your SQL queries');
    }
    $query = 'select ' . TABLE_CLUBS_PRODUCTION . '.id as id';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.name as name';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.latitude';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.longtitude';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.website';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.email';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.phone';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.comment';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.opening_time';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.closing_time';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.price_member';
    $query .= ', ' . TABLE_CLUBS_PRODUCTION . '.price_nonmember';
    $query .= ' from ' . TABLE_CLUBS_PRODUCTION . '';
    $query .= ', ' . $junction_table;
    if ( $data !== [] ) {
      $query .= ', ' . $entity_table;
    }
    $query .= ' where';
    if ( ! is_null ($time) ) {
      $query .= ' not (' . TABLE_CLUBS_PRODUCTION . '.opening_time is not null';
      $query .= ' and ' . TABLE_CLUBS_PRODUCTION . '.closing_time is not null';
      $query .= " and '{$time['close']}' <= " . TABLE_CLUBS_PRODUCTION . '.opening_time';
      $query .= ' or ' . TABLE_CLUBS_PRODUCTION . ".closing_time <= '{$time['open']}') and";
    }
    if ( isset ($price ['member']) )
    {
      $query .= ' (' . TABLE_CLUBS_PRODUCTION . '.price_member is null';
      $query .= ' or ' . TABLE_CLUBS_PRODUCTION . ".price_member <= {$price ['member']}) and";
    }
    if ( isset ($price ['nonmember']) )
    {
      $query .= ' (' . TABLE_CLUBS_PRODUCTION . '.price_nonmember is null';
      $query .= ' or ' . TABLE_CLUBS_PRODUCTION . ".price_nonmember <= {$price ['nonmember']}) and";
    }
    $query .= ' ' . TABLE_CLUBS_PRODUCTION . ".id = {$junction_table}.club_id and";
    if ( $data !== [] )
    {
      $query .= " {$entity_table}.id = {$junction_table}.{$entity_id} and";
      $query .= ' ' . $entity_table . '.name in (';
      $data = array_values ( $data );
      foreach ( $data as $entity )
      {
        $query .= "'{$entity}', ";
      }
      $query = substr ($query, 0, -2);
      $query .= ') and';
    }
    $query .= ' (';
    foreach ( $days as $day )
    {
      $query .= '(';
      if ( $days_selected ) {
        $query .= "{$junction_table}.day_id = {$day} and";
      }
      if ( isset ($price ['member']) )
      {
        $query .= " ({$junction_table}.price_member is null";
        $query .= " or {$junction_table}.price_member <= {$price ['member']}) and";
      }
      if ( isset ($price ['nonmember']) )
      {
        $query .= " ({$junction_table}.price_nonmember is null";
        $query .= " or {$junction_table}.price_nonmember <= {$price ['nonmember']}) and";
      }
      if ( ! is_null ($time) ) {
        $query .= ' not (' . TABLE_CLUBS_PRODUCTION . '.opening_time is not null';
        $query .= ' and ' . TABLE_CLUBS_PRODUCTION . '.closing_time is not null';
        $query .= " and '{$time['close']}' <= {$junction_table}.opening_time";
        $query .= " or {$junction_table}.closing_time <= '{$time['open']}') and";
      }
      $query = substr ($query, 0, -4);
      $query .= ') or ';
      if ( ! $days_selected ) {
        break;
      }
    }
    $query = substr ($query, 0, -4);
    $query .= ')';
    $query .= ' group by id';
#   $query .= ' order by id';
#   echoQuery ($query);
    return wh_db_query ( $query );
  }

  function getSports ( )
  {
    return wh_db_query ( 'select * from ' . TABLE_SPORTS );
  }

  function getFacilities ( )
  {
    return wh_db_query ( 'select * from ' . TABLE_FACILITIES );
  }

  /**
   * @return sports that are practised in at least one club
   */
  function getSportsNotLeaves ( )
  {
    $query = 'select ' . TABLE_SPORTS . '.*' .
             ' from ' . TABLE_SPORTS . ', ' . TABLE_CLUBOSPORT_PRODUCTION .
             ' where '. TABLE_SPORTS .'.id = '. TABLE_CLUBOSPORT_PRODUCTION . '.sport_id' .
             ' group by ' . TABLE_SPORTS . '.id';
    return wh_db_query ( $query );
  }

  /**
   * @return facilities that are available in at least one club
   */
  function getFacilitiesNotLeaves ( )
  {
    $query = 'select ' . TABLE_FACILITIES . '.*' .
             ' from ' . TABLE_FACILITIES . ', ' . TABLE_CLUB_FACILITIES_PRODUCTION .
             ' where ' . TABLE_SPORTS . '.id = ' .
             TABLE_CLUB_FACILITIES_PRODUCTION . '.facility_id' .
             ' group by ' . TABLE_FACILITIES . '.id';
    return wh_db_query ( $query );
  }

  function getSportsOrderById ( )
  {
    return wh_db_query ( 'select * from ' . TABLE_SPORTS . ' order by id' );
  }

  function getFacilitiesOrderById ( )
  {
    return wh_db_query ( 'select * from ' . TABLE_FACILITIES . ' order by id' );
  }

  function getSportsOrderByName ( )
  {
    return wh_db_query ( 'select * from ' . TABLE_SPORTS . ' order by name' );
  }

  function getFacilitiesOrderByName ( )
  {
    return wh_db_query ( 'select * from '. TABLE_FACILITIES .' order by name' );
  }

  function getSportByName ( $sport )
  {
    return wh_db_query ('select * from '.TABLE_SPORTS." where name='{$sport}'");
  }

  function getFacilityByName ( $facility )
  {
    return wh_db_query ( 'select * from ' . TABLE_FACILITIES .
                         " where name='{$facility}'" );
  }

  function getSportsByClub ( $club )
  {
    $query = 'select ' . TABLE_SPORTS . '.name' .
      ' from ' . TABLE_SPORTS . ', ' . TABLE_CLUBOSPORT_PRODUCTION .
      ' where ' . TABLE_CLUBOSPORT_PRODUCTION . '.club_id = ' . $club .
      ' and ' . TABLE_SPORTS . '.id = ' . TABLE_CLUBOSPORT_PRODUCTION . '.sport_id';
    $query .= ' group by ' . TABLE_SPORTS . '.name';
    return wh_db_query ( $query );
  }

  function getFacilitiesByClub ( $club )
  {
    $query = 'select ' . TABLE_FACILITIES . '.name' .
      ' from ' . TABLE_FACILITIES . ', ' . TABLE_CLUB_FACILITIES_PRODUCTION .
      ' where ' . TABLE_CLUB_FACILITIES_PRODUCTION . '.club_id = ' . $club .
      ' and '. TABLE_FACILITIES.'.id = '. TABLE_CLUB_FACILITIES_PRODUCTION .'.facility_id';
    $query .= ' group by ' . TABLE_FACILITIES . '.name';
    return wh_db_query ( $query );
  }

  function getSportsByClubOrderByNameDays ( $club )
  {
    $query = 'select distinct sport_id, day_id, ' .
      'price_member, price_nonmember, opening_time, closing_time' .
      ' from ' . TABLE_SPORTS . ', ' . TABLE_CLUBOSPORT_PRODUCTION . '' .
      ' where ' . TABLE_CLUBOSPORT_PRODUCTION . '.club_id = ' . $club .
      ' and ' . TABLE_SPORTS . '.id = ' . TABLE_CLUBOSPORT_PRODUCTION . '.sport_id';
#   $query .= ' group by '. TABLE_SPORTS.'.name, '. TABLE_CLUBOSPORT_PRODUCTION .'.day_id';
    $query .= ' order by ' . TABLE_SPORTS . '.name, day_id';
#   echoQuery ( $query );
    return wh_db_query ( $query );
  }

  function getFacilitiesByClubOrderByNameDays ( $club )
  {
    $query = 'select distinct sport_id, day_id, ' .
      'price_member, price_nonmember, opening_time, closing_time' .
      ' from ' . TABLE_FACILITIES . ', ' . TABLE_CLUB_FACILITIES_PRODUCTION .
      ' where ' . TABLE_CLUB_FACILITIES_PRODUCTION . '.club_id = ' . $club .
      ' and '. TABLE_FACILITIES .'.id = ' . TABLE_CLUB_FACILITIES_PRODUCTION . '.sport_id';
#   $query .= ' group by ' . TABLE_FACILITIES . '.name, ' .
#             TABLE_CLUB_FACILITIES_PRODUCTION . '.day_id';
    $query .= ' order by ' . TABLE_FACILITIES . '.name, day_id';
#   echoQuery ( $query );
    return wh_db_query ( $query );
  }

  /**
   * @param $table entity table - sports or facilities
   * @param $club id of the club
   * @param $entity id of entity - sport or facility
   * @param $times array with time schedules
   * @return query result
   */
  function setSportsTime ( $table, $club, $entity, $times )
  {
    if ( $table == 'sports' )
    {
      $junction_table = TABLE_CLUBOSPORT;
      $entity_id = 'sport_id';
    }
    else if ( $table == 'facilities' )
    {
      $junction_table = TABLE_CLUB_FACILITIES;
      $entity_id = 'facility_id';
    } else {
      wh_error ('Check your SQL queries');
    }
    if ( array_diff(array_keys($times), range(1, 10)) !== [] ){
      var_dump ($times);
      wh_error ('Check the times');
    }
    $query = "insert into {$junction_table} ( club_id, {$entity_id}, day_id, "
           . 'opening_time, closing_time ) values';
    foreach ($times as $day => $time)
    {
      $query .= " ( {$club}, {$entity}, {$day}, '{$time['open']}', "
              . "'{$time['close']}' ),";
    }
    $query = rtrim($query, ',');
    $query .= ' on duplicate key update opening_time=values(opening_time), '
            . 'closing_time=values(closing_time);';
#   echoQuery ( $query );
    return wh_db_query ( $query );
  }

  /**
   * @param $table entity table - sports or facilities
   * @param $club id of the club
   * @param $entity id of entity - sport or facility
   * @param $prices array with prices
   * @return query result
   */
  function setSportsPrice ( $table, $club, $entity, $prices )
  {
    if ( $table == 'sports' )
    {
      $junction_table = TABLE_CLUBOSPORT;
      $entity_id = 'sport_id';
    }
    else if ( $table == 'facilities' )
    {
      $junction_table = TABLE_CLUB_FACILITIES;
      $entity_id = 'facility_id';
    } else {
      wh_error ('Check your SQL queries');
    }
    if ( array_diff(array_keys($prices), range(1, 10)) !== [] ){
      var_dump ($prices);
      wh_error ('Check the prices');
    }
    $query = "insert into {$junction_table} ( club_id, {$entity_id}, day_id, "
           . 'price_member, price_nonmember ) values';
    foreach ($prices as $day => $price)
    {
      $query .= " ( {$club}, {$entity}, {$day}, {$price['member']}, "
              . "{$price['nonmember']} ),";
    }
    $query = rtrim($query, ',');
    $query .= ' on duplicate key update price_member=values(price_member), '
            . 'price_nonmember=values(price_nonmember);';
#   echoQuery ( $query );
    return wh_db_query ( $query );
  }

  /**
   * @param $table entity table - sports or facilities
   * @param $club id of the club
   * @param $entity id of entity - sport or facility
   * @param $times array with time schedules
   * @param $prices array with prices
   * @return query result
   */
  function setSportsTimePrice ( $table, $club, $entity, $times, $prices )
  {
    if ( $table == 'sports' )
    {
      $junction_table = TABLE_CLUBOSPORT;
      $entity_id = 'sport_id';
    }
    else if ( $table == 'facilities' )
    {
      $junction_table = TABLE_CLUB_FACILITIES;
      $entity_id = 'facility_id';
    } else {
      wh_error ('Check your SQL queries');
    }
    if ( array_diff(array_keys($times), range(1, 10)) !== [] ){
      var_dump ($times);
      wh_error ('Check the times');
    }
    if ( array_diff(array_keys($prices), range(1, 10)) !== [] ){
      var_dump ($prices);
      wh_error ('Check the prices');
    }
    if ( array_diff(array_keys($times),  array_keys($prices)) !== [] ||
         array_diff(array_keys($prices), array_keys($times))  !== [] ){
      var_dump ($times);
      var_dump ($prices);
      wh_error ('Check the times and prices');
    }
    $query = "insert into {$junction_table} ( club_id, {$entity_id}, day_id, "
           . 'opening_time, closing_time, price_member, price_nonmember ) '
           . 'values';
    foreach ($times as $day => $time)
    {
      $query .= " ( {$club}, {$entity}, {$day}, "
              . "'{$time['open']}', '{$time['close']}', "
              . "{$prices[$day]['member']}, {$prices[$day]['nonmember']} ),";
    }
    $query = rtrim($query, ',');
    $query .= ' on duplicate key update '
            . 'opening_time=values(opening_time), '
            . 'closing_time=values(closing_time), '
            . 'price_member=values(price_member), '
            . 'price_nonmember=values(price_nonmember);';
#   echoQuery ( $query );
    return wh_db_query ( $query );
  }

  /**
   * @param $table entity table - sports or facilities
   * @param $club id of the club
   * @param $entity id of entity - sport or facility
   * @return query result
   */
  function deleteSportsTimePrice ( $table, $club, $entity )
  {
    if ( $table == 'sports' )
    {
      $junction_table = TABLE_CLUBOSPORT;
      $entity_id = 'sport_id';
    }
    else if ( $table == 'facilities' )
    {
      $junction_table = TABLE_CLUB_FACILITIES;
      $entity_id = 'facility_id';
    } else {
      wh_error ('Check your SQL queries');
    }
    $query = "update {$junction_table} set opening_time=null, closing_time=null, "
           . 'price_member=null, price_nonmember=null '
           . "where club_id = {$club} and {$entity_id} = {$entity};";
    wh_db_query ( $query );
  }

  /**
   * Deletes empty entries from clubosport
   */
  function cleanClubosport ( $club )
  {
    $query = 'delete from ' . TABLE_CLUBOSPORT . ' where club_id = ' . $club
           . ' and (opening_time is null or opening_time = \'00:00:00\' or '
           . 'closing_time is null or closing_time = \'00:00:00\') '
           . 'and price_member is null and price_nonmember is null;';
#   echoQuery ( $query );
    return wh_db_query ( $query );
  }

  /**
   * Deletes empty entries from club_facilities
   */
  function cleanClub_facilities ( $club )
  {
    $query = 'delete from '. TABLE_CLUB_FACILITIES .' where club_id = ' . $club
           . 'and (opening_time is null or closing_time is null) '
           . 'and price_member is null and price_nonmember is null;';
#   echoQuery ( $query );
    return wh_db_query ( $query );
  }

  /**
   * Creates temporary database tables,
   * dropping old temporary tables if they exist.
   */
  function create_temporary_tables ( )
  {
    $query = 'drop table if exists ' . TABLE_CLUB_SCHEDULE .
              ', ' . TABLE_CLUBOSPORT .
              ', ' . TABLE_CLUB_FACILITIES .
              ', ' . TABLE_CLUBS .
              ', ' . TABLE_CLUB_SCHEDULE_OLD .
              ', ' . TABLE_CLUBOSPORT_OLD .
              ', ' . TABLE_CLUB_FACILITIES_OLD .
              ', ' . TABLE_CLUBS_OLD;
    wh_db_query ( $query );

    $constant = 'constant';

    $query = <<<MYSQL
CREATE TABLE `{$constant('TABLE_CLUBS')}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_bin NOT NULL,
  `address` varchar(300) COLLATE utf8_bin DEFAULT NULL,
  `postcode` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longtitude` double DEFAULT NULL,
  `website` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `comment` varchar(4000) COLLATE utf8_bin DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `price_member` float DEFAULT NULL,
  `price_nonmember` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
MYSQL;

    wh_db_query ( $query );

    $query = <<<MYSQL
CREATE TABLE `{$constant('TABLE_CLUB_SCHEDULE')}` (
  `club_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL DEFAULT '8',
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `price_member` float DEFAULT NULL,
  `price_nonmember` float DEFAULT NULL,
  PRIMARY KEY (`club_id`,`day_id`),
  KEY `club_id` (`club_id`),
  KEY `day_id` (`day_id`),
  CONSTRAINT FOREIGN KEY (`club_id`) REFERENCES `{$constant('TABLE_CLUBS')}` (`id`) ON DELETE CASCADE,
  CONSTRAINT FOREIGN KEY (`day_id`) REFERENCES `{$constant('TABLE_DAYS')}` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
MYSQL;

    wh_db_query ( $query );

    $query = <<<MYSQL
CREATE TABLE `{$constant('TABLE_CLUBOSPORT')}` (
  `club_id` int(11) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL DEFAULT '8',
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `price_member` float DEFAULT NULL,
  `price_nonmember` float DEFAULT NULL,
  PRIMARY KEY (`club_id`,`sport_id`,`day_id`),
  KEY `club_id` (`club_id`),
  KEY `sport_id` (`sport_id`),
  KEY `day_id` (`day_id`),
  CONSTRAINT FOREIGN KEY (`club_id`) REFERENCES `{$constant('TABLE_CLUBS')}` (`id`) ON DELETE CASCADE,
  CONSTRAINT FOREIGN KEY (`sport_id`) REFERENCES `{$constant('TABLE_SPORTS')}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`day_id`) REFERENCES `{$constant('TABLE_DAYS')}` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
MYSQL;

    wh_db_query ( $query );

    $query = <<<MYSQL
CREATE TABLE `{$constant('TABLE_CLUB_FACILITIES')}` (
  `club_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL DEFAULT '8',
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `price_member` float DEFAULT NULL,
  `price_nonmember` float DEFAULT NULL,
  PRIMARY KEY (`club_id`,`facility_id`,`day_id`),
  KEY `club_id` (`club_id`),
  KEY `facility_id` (`facility_id`),
  KEY `day_id` (`day_id`),
  CONSTRAINT FOREIGN KEY (`club_id`) REFERENCES `{$constant('TABLE_CLUBS')}` (`id`) ON DELETE CASCADE,
  CONSTRAINT FOREIGN KEY (`facility_id`) REFERENCES `{$constant('TABLE_FACILITIES')}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`day_id`) REFERENCES `{$constant('TABLE_DAYS')}` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
MYSQL;

    wh_db_query ( $query );
    return;
  }

  /**
   * Drops production database tables and
   * renames temporary tables to production tables.
   */
  function change_production_tables ( )
  {
    $query = 'rename table ' .
        TABLE_CLUBS_PRODUCTION . ' to ' . TABLE_CLUBS_OLD .
        ', '. TABLE_CLUB_SCHEDULE_PRODUCTION .' to ' . TABLE_CLUB_SCHEDULE_OLD .
        ', '. TABLE_CLUBOSPORT_PRODUCTION . ' to ' . TABLE_CLUBOSPORT_OLD .
        ', '.TABLE_CLUB_FACILITIES_PRODUCTION .' to '.TABLE_CLUB_FACILITIES_OLD.
        ', ' . TABLE_CLUBS . ' to ' . TABLE_CLUBS_PRODUCTION .
        ', '. TABLE_CLUB_SCHEDULE . ' to ' . TABLE_CLUB_SCHEDULE_PRODUCTION .
        ', '. TABLE_CLUBOSPORT . ' to ' . TABLE_CLUBOSPORT_PRODUCTION .
        ', ' . TABLE_CLUB_FACILITIES .' to ' . TABLE_CLUB_FACILITIES_PRODUCTION;
    wh_db_query ( $query );

    $query = 'drop table ' . TABLE_CLUB_SCHEDULE_OLD .
              ', ' . TABLE_CLUBOSPORT_OLD .
              ', ' . TABLE_CLUB_FACILITIES_OLD .
              ', ' . TABLE_CLUBS_OLD;
    wh_db_query ( $query );

    $query = 'optimize table ' . TABLE_CLUBS_PRODUCTION .
              ', ' . TABLE_CLUB_SCHEDULE_PRODUCTION .
              ', ' . TABLE_CLUBOSPORT_PRODUCTION .
              ', ' . TABLE_CLUB_FACILITIES_PRODUCTION .
              ', ' . TABLE_SPORTS;
    wh_db_query ( $query );

    return;
  }

  function wh_determine_best_view_prices ( $prices )
  {
    for ( $i = 1; $i < 5; ++$i )
    {
      if ( $prices [$i] ['member']    != $prices [$i+1] ['member'] ||
           $prices [$i] ['nonmember'] != $prices [$i+1] ['nonmember'] )
      {
        return 'separately';
      }
    }
    // if data type not set yet, check if at least workweek days identical
    if ( $prices [6] ['member']    != $prices [7] ['member'] ||
         $prices [6] ['nonmember'] != $prices [7] ['nonmember'] )
    {
      return 'workweeksatsun';
    }
    if ( $prices [5] ['member']    == $prices [6] ['member'] &&
         $prices [6] ['nonmember'] == $prices [7] ['nonmember']  )
    {
      return 'all';
    }
    return 'workweekweekend';
  }

  function wh_determine_best_view_times ( $times )
  {
    for ( $i = 1; $i < 5; ++$i )
    {
      if ( $times [$i] ['open']  != $times [$i+1] ['open'] ||
           $times [$i] ['close'] != $times [$i+1] ['close'] )
      {
        return 'separately';
      }
    }
    // if data type not set yet, check if at least workweek days identical
    if ( $times [6] ['open']  != $times [7] ['open'] ||
         $times [6] ['close'] != $times [7] ['close'] )
    {
      return 'workweeksatsun';
    }
    if ( $times [5] ['open']  == $times [6] ['open'] &&
         $times [6] ['close'] == $times [7] ['close']  )
    {
      return 'all';
    }
    return 'workweekweekend';
  }

  function wh_times_prices_assoc_to_num ( $array, $type )
  {
    switch ( $type )
    {
    case 'separately':
      return $array;
    case 'all':
      return array_fill ( 1, 7, $array [8] );
    case 'workweekweekend':
      $array_t = array_fill ( 1, 5, $array [9] );
      $array_t [6] = $array_t [7] = $array [10];
      return $array_t;
    case 'workweeksatsun':
      $array_t = array_fill ( 1, 5, $array [9] );
      $array_t [6] = $array [6];
      $array_t [7] = $array [7];
      return $array_t;
    }
  }

  function wh_times_prices_num_to_assoc ( $array, $type )
  {
    switch ( $type )
    {
    case 'separately':
      return $array;
    case 'all':
      return [ 8 => $array[1] ];
    case 'workweekweekend':
      return [ 9 => $array [1], 10 => $array [6] ];
    case 'workweeksatsun':
      return [ 6 => $array [6], 7 => $array [7],
               9 => $array [1] ];
    }
  }

?>
