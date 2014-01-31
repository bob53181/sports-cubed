<?php
	function editClub ( )
	{
		if ( wh_db_post_input_string ( 'action' ) != 'update' )
		{
			return;
		}

		global $club;

		if ( is_null ( $club ) || is_null ( $club->id ) )
		{
			return;
		}

		$club_id = $club->id;

		$name = wh_db_post_input_string ( 'name' );

		if ( ! wh_not_null ( $name ) )
		{
			$name = $club->name;
		}
		else if ( $club_t = wh_db_fetch_object_custom ( getClubByName ( $name ) ) )
		{
			if ( ! is_null ( $club_t ) && $club_t->id != $club_id )
			{
#				var_dump (get_object_vars($club));
#				var_dump (get_object_vars($club_t));
				wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">There is another club with the same name</strong>' );
				return;
			}
		}

		$address = wh_db_post_input_string ( 'address' );
		$postcode = wh_db_post_input_string( 'postcode' );
		$latitude = wh_db_post_input_string ( 'latitude' );

		if ( wh_not_null ($latitude) && ! is_numeric ( $latitude ) )
		{
			wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">Latitude is not numeric</strong>' );
			return;
		}

		$longtitude = wh_db_post_input_string ( 'longtitude' );

		if ( wh_not_null ($longtitude) && ! is_numeric ( $longtitude ) )
		{
			wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">Longtitude is not numeric</strong>' );
			return;
		}

		$comment = wh_db_post_input_string ( 'comment' );

		if ( wh_not_null ( $comment ) && strlen ( $comment ) > 4000 ) {
			wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">Comment is too long &mdash; over 4000 symbols</strong>' );
			return;
		}

		$email = wh_db_post_input_string ( 'email' );

		$phone = wh_db_post_input_string ( 'phone' );

		$sports_query = getSports ();

		while ( $sport = wh_db_fetch_object_custom($sports_query) )
		{
			$sport_id = $sport->id;
			$select_days_view_time = wh_db_post_input_string ( "selectDaysViewTime{$sport_id}" );
			$select_days_view_price = wh_db_post_input_string ( "selectDaysViewPrice{$sport_id}" );
			$select_days_view = ( $select_days_view_time == $select_days_view_price )
				? $select_days_view_time : null;
			if ( $select_days_view == 'all' )
			{
				$times = array();
				$prices = array();
				$time_open = wh_db_post_input_string ( "timeOpenAll{$sport_id}" );
				$time_close = wh_db_post_input_string ( "timeCloseAll{$sport_id}" );
				$price_member = wh_db_post_input_string ( "priceMemberAll{$sport_id}" );
				$price_nonmember = wh_db_post_input_string ( "priceNonmemberAll{$sport_id}" );
				if ( $time_open == '00:00:00' ) {
					$time_open = null;
				}
				if ( $time_close == '00:00:00' ) {
					$time_close = null;
				}
				if ( $price_member != null && (float) $price_member == 0.0 ) {
					$price_member = null;
				}
				if ( $price_nonmember != null && (float) $price_nonmember == 0.0 ) {
					$price_nonmember = null;
				}
				if ( wh_not_null ( $time_open ) && wh_not_null ( $time_close ) )
				{
#					$times = array();
					$times['open'] = $time_open;
					$times['close'] = $time_close;
				}
				if ( wh_not_null ( $price_member ) || wh_not_null ( $price_nonmember ) )
				{
#					$prices = array();
					if ( wh_not_null ( $price_member ) ) {
						$prices['member'] = $price_member;
					}
					if ( wh_not_null ( $price_nonmember ) ) {
						$prices['nonmember'] = $price_nonmember;
					}
				}
				if ( count ( $times ) && count ( $prices ) ) {
					setSportsTimePriceAll ( $club_id, $sport_id, $times, $prices );
				}
				elseif ( count ( $times ) ) {
					setSportsTimeAll ( $club_id, $sport_id, $times );
				}
				elseif ( count ( $prices ) ) {
					setSportsPriceAll ( $club_id, $sport_id, $prices );
				}
			}
			elseif ( $select_days_view == 'working' )
			{
				$time_open_working = wh_db_post_input_string ( "timeOpenWorking{$sport_id}" );
				$time_open_weekend = wh_db_post_input_string ( "timeOpenWeekend{$sport_id}" );
				$time_close_working = wh_db_post_input_string ( "timeCloseWorking{$sport_id}" );
				$time_close_weekend = wh_db_post_input_string ( "timeCloseWeekend{$sport_id}" );
				$price_member_working = wh_db_post_input_string ( "priceMemberWorking{$sport_id}" );
				$price_member_weekend = wh_db_post_input_string ( "priceMemberWeekend{$sport_id}" );
				$price_nonmember_working = wh_db_post_input_string ( "priceNonmemberWorking{$sport_id}" );
				$price_nonmember_weekend = wh_db_post_input_string ( "priceNonmemberWeekend{$sport_id}" );
				setSportsTimePriceWorking ( $club_id, $sport_id, $times, $prices );
			}
			elseif ( $select_days_view == 'separately' )
			{
				$times = array();
				$prices = array();
				for ($i = 1; $i < 8; ++$i)
				{
					$time_open = wh_db_post_input_string ( "timeOpenDay{$i}_{$sport_id}" );
					$time_close = wh_db_post_input_string ( "timeCloseDay{$i}_{$sport_id}" );
					$price_member = wh_db_post_input_string ( "priceMemberDay{$i}_{$sport_id}" );
					$price_nonmember = wh_db_post_input_string ( "priceNonmemberDay{$i}_{$sport_id}" );
					if ( $time_open == '00:00:00' ) {
						$time_open = null;
					}
					if ( $time_close == '00:00:00' ) {
						$time_close = null;
					}
					if ( $price_member != null && (float) $price_member == 0.0 ) {
						$price_member = null;
					}
					if ( $price_nonmember != null && (float) $price_nonmember == 0.0 ) {
						$price_nonmember = null;
					}
					if ( wh_not_null ( $time_open ) && wh_not_null ( $time_close ) )
					{
						$times[$i] = array();
						$times[$i]['open'] = $time_open;
						$times[$i]['close'] = $time_close;
					}
					if ( wh_not_null ( $price_member ) || wh_not_null ( $price_nonmember ) )
					{
						$prices[$i] = array();
						if ( wh_not_null ( $price_member ) ) {
							$prices[$i]['member'] = $price_member;
						}
						if ( wh_not_null ( $price_nonmember ) ) {
							$prices[$i]['nonmember'] = $price_nonmember;
						}
					}
				}
				if ( count ( $times ) ) {
					setSportsTimeSeparately ( $club_id, $sport_id, $times );
				}
				if ( count ( $prices ) ) {
					setSportsPriceSeparately ( $club_id, $sport_id, $prices );
				}
			}
			else
			{
				if ( $select_days_view_time == 'all' )
				{
					$time_open = wh_db_post_input_string ( "timeOpenAll{$sport_id}" );
					$time_close = wh_db_post_input_string ( "timeCloseAll{$sport_id}" );
				}
				elseif ( $select_days_view_time == 'working' )
				{
					$time_open_working = wh_db_post_input_string ( "timeOpenWorking{$sport_id}" );
					$time_open_weekend = wh_db_post_input_string ( "timeOpenWeekend{$sport_id}" );
					$time_close_working = wh_db_post_input_string ( "timeCloseWorking{$sport_id}" );
					$time_close_weekend = wh_db_post_input_string ( "timeCloseWeekend{$sport_id}" );
				}
				elseif ( $select_days_view_time == 'separately' )
				{
					$times = array();
					for ($i = 1; $i < 8; ++$i)
					{
						$time_open = wh_db_post_input_string ( "timeOpenDay{$i}_{$sport_id}" );
						$time_close = wh_db_post_input_string ( "timeCloseDay{$i}_{$sport_id}" );
						if ( $time_open == '00:00:00' ) {
							$time_open = null;
						}
						if ( $time_close == '00:00:00' ) {
							$time_close = null;
						}
						if ( wh_not_null ( $time_open ) && wh_not_null ( $time_close ) )
						{
							$times[$i] = array();
							$times[$i]['open'] = $time_open;
							$times[$i]['close'] = $time_close;
						}
					}
					if ( count ( $times ) ) {
						setSportsTimeSeparately ( $club_id, $sport_id, $times );
					}
				}
				if ( $select_days_view_price == 'all' )
				{
					$price_member = wh_db_post_input_string ( "priceMemberAll{$sport_id}" );
					$price_nonmember = wh_db_post_input_string ( "priceNonmemberAll{$sport_id}" );
				}
				elseif ( $select_days_view_price == 'working' )
				{
					$price_member_working = wh_db_post_input_string ( "priceMemberWorking{$sport_id}" );
					$price_member_weekend = wh_db_post_input_string ( "priceMemberWeekend{$sport_id}" );
					$price_nonmember_working = wh_db_post_input_string ( "priceNonmemberWorking{$sport_id}" );
					$price_nonmember_weekend = wh_db_post_input_string ( "priceNonmemberWeekend{$sport_id}" );
				}
				elseif ( $select_days_view_price == 'separately' )
				{
					$prices = array();
					for ($i = 1; $i < 8; ++$i)
					{
						$price_member = wh_db_post_input_string ( "priceMemberDay{$i}_{$sport_id}" );
						$price_nonmember = wh_db_post_input_string ( "priceNonmemberDay{$i}_{$sport_id}" );
						if ( $price_member != null && (float) $price_member == 0.0 ) {
							$price_member = null;
						}
						if ( $price_nonmember != null && (float) $price_nonmember == 0.0 ) {
							$price_nonmember = null;
						}
						if ( wh_not_null ( $price_member ) || wh_not_null ( $price_nonmember ) )
						{
							$prices[$i] = array();
							if ( wh_not_null ( $price_member ) ) {
								$prices[$i]['member'] = $price_member;
							}
							if ( wh_not_null ( $price_nonmember ) ) {
								$prices[$i]['nonmember'] = $price_nonmember;
							}
						}
					}
					if ( count ( $prices ) ) {
						setSportsPriceSeparately ( $club_id, $sport_id, $prices );
						//function does not exist
					}
				}
			}
		}
		//INSERT INTO clubosport (club_id, sport_id, day_id, price_nonmember)
		//VALUES (102, 1, 2, 60)
		//ON DUPLICATE KEY UPDATE price_nonmember=60

		$time_open = wh_db_post_input_string ( 'time_open' );

// previous to PHP 5.1.0 you would compare with -1, instead of false
		//if (($timestamp = strtotime($time_open)) === false) {
		if ( wh_not_null ($time_open) && strtotime($time_open) === false ) {
			wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">Opening time is not a valid time</strong>' );
			return;
		}
		//else {
			//die ("$time_open == " . date('l dS \o\f F Y h:i:s A', $timestamp));
		//}

		$time_close = wh_db_post_input_string ( 'time_close' );

		if ( wh_not_null ($time_close) && strtotime($time_close) === false ) {
			wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">Closing time is not a valid time</strong>' );
			return;
		}

		$price_member = wh_db_post_input_string ( 'price_member' );

		if ( wh_not_null ($price_member) && ! is_numeric ( $price_member ) )
		{
			wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">Members price is not numeric</strong>' );
			return;
		}

		$price_nonmember = wh_db_post_input_string ( 'price_nonmember' );

		if ( wh_not_null ($price_nonmember) && ! is_numeric ( $price_nonmember ) )
		{
			wh_define ( 'TEXT_ERROR', '<strong style="color: #FF0000">Non members price is not numeric</strong>' );
			return;
		}

		$everyday = wh_db_post_input_prepare ( 'everyday' );
		if ( $everyday != true )
		{
			$days = array ( );
			if ( wh_db_post_input_check ( 'monday' ) )
				$days ['monday'] = 1;
			if ( wh_db_post_input_check ( 'tuesday' ) )
				$days ['tuesday'] = 2;
			if ( wh_db_post_input_check ( 'wednesday' ) )
				$days ['wednesday'] = 3;
			if ( wh_db_post_input_check ( 'thursday' ) )
				$days ['thursday'] = 4;
			if ( wh_db_post_input_check ( 'friday' ) )
				$days ['friday'] = 5;
			if ( wh_db_post_input_check ( 'saturday' ) )
				$days ['saturday'] = 6;
			if ( wh_db_post_input_check ( 'sunday' ) )
				$days ['sunday'] = 7;
		}
		$sports = wh_db_post_input_prepare_array ( 'sports' );

		// as of PHP 5.4
		$data = [
#			"id" => $club_id,
			"name" => $name,
			"address" => $address,
			"postcode" => $postcode,
			"latitude" => $latitude,
			"longtitude" => $longtitude,
			"comment" => $comment,
			"email" => $email,
			"phone" => $phone
		];

		wh_db_perform ( 'clubs', $data, 'update', "id = '{$club_id}'"  );
		unset ( $data );

		//foreach do not check if sports is null
/*
		if ( $everyday == true )
		{
			foreach ( $sports as $sport )
			{
				$data = [
					"club_id" => $club_id,
					"sport_id" => $sport,
					"price_member" => $price_member,
					"price_nonmember" => $price_nonmember,
					"opening_time" => $time_open,
					"closing_time" => $time_close
					//"day_id" => 8
				];
				wh_db_perform ( 'clubosport', $data );
				unset ( $data );
			}
		}	//if ( #everyday...
		else
		{
			if ( ! is_array ( $days ) )
				wh_error ( 'days is not array' );
			foreach ( $sports as $sport )
			{
				foreach ( $days as $day )
				{
					$data = [
						"club_id" => $club_id,
						"sport_id" => $sport,
						"day_id" => $day,
						"price_member" => $price_member,
						"price_nonmember" => $price_nonmember,
						"opening_time" => $time_open,
						"closing_time" => $time_close
					];
					wh_db_perform ( 'clubosport', $data, 'update' );
					unset ( $data );
				}	//foreach ( $days...
			}	//foreach ( $sports...
		}	//if ( $everyday... else...
*/

/*
		$bash_sum = 0.0;
		$bash_max = 0.0;
		for ( $i = 0; $i < 100; ++$i )
		{
			$starttime = microtime(true);
			exec ('echo `date +%s`  > /var/www/html/database/newest.txt');
			$endtime = microtime(true);
			$t = $endtime - $starttime;
			if ( $t > $bash_max )
				$bash_max = $t;
			$bash_sum += $t;
		}

		$php_sum = 0.0;
		$php_max = 0.0;
		for ( $i = 0; $i < 100; ++$i )
		{
			$starttime = microtime(true);
			file_put_contents ( '/var/www/html/database/newest.txt', time () );
			$endtime = microtime(true);
			$t = $endtime - $starttime;
			if ( $t > $php_max )
				$php_max = $t;
			$php_sum += $t;
		}

		echo 'Average bash = ', $bash_sum / 100.0, ' <br />', PHP_EOL;
		echo 'Average php = ', $php_sum / 100.0, ' <br />', PHP_EOL;
		echo 'Max bash = ', $bash_max, ' <br />', PHP_EOL;
		echo 'Max php = ', $php_max, ' <br />', PHP_EOL;
*/

		file_put_contents ( '/var/www/html/database/newest.txt', time () . PHP_EOL );
		// will write the Unix timestamp to newest.txt

		wh_define ( 'TEXT_SUCCESS', '<strong style="color: #00FF00">Successfully edited a club</strong>' );


	}

	function selectClub ( )
	{
/*		if ( wh_db_post_input_string ( 'action' ) == 'update'
			&& wh_not_null ( $club ) && wh_not_null( $club->name ) )
		{
			return;
		}
*/
		$action = wh_db_post_input_string ( 'action' );
		$club_search = wh_db_get_input_string ( 'club_search' );
		$sport_search = wh_db_get_input_string ( 'sport_search' );
		$id = wh_db_post_input_string ( 'id' );

//Return the actual query, not the last query - no use for it now
#		$url_query = parse_url ( $_SERVER['REQUEST_URI'], PHP_URL_QUERY );
#		parse_str ( $url_query, $get_vars );
#		var_dump ( $club_search );
#		var_dump ( $sport_search );
#		var_dump ( $get_vars['club_search'] );
#		var_dump ( $get_vars['sport_search'] );
#		var_dump ( $url_query );
#		die ();

//Should check what club and sport were selected previously

		if ( wh_not_null ( $club_search ) && wh_not_null ( $sport_search ) &&
			$club_search != 0 && $sport_search != 0 && wh_null ( $action ) )
		{
#			if ( wh_not_null ( $get_vars['sport_search'] ) && $get_vars['sport_search'] != 0 ) {
#				header ( 'Location: edit.php?club_search=' . $club_search );
#			}
#			if ( wh_not_null ( $get_vars['club_search'] ) && $get_vars['club_search'] != 0 ) {
#				header ( 'Location: edit.php?sport_search=' . $sport_search );
#			}
			header ( 'Location: edit.php?club_search=' . $club_search );
		}

		if ( wh_not_null ( $club_search ) && wh_not_null ( $sport_search ) &&
			$club_search != 0 && $sport_search == 0 && wh_null ( $action ) )
		{
			header ( 'Location: edit.php?club_search=' . $club_search );
		}

		if ( wh_not_null ( $club_search ) && wh_not_null ( $sport_search ) &&
			$club_search == 0 && $sport_search != 0 && wh_null ( $action ) )
		{
			header ( 'Location: edit.php?sport_search=' . $sport_search );
		}

		if ( wh_not_null ( $club_search ) && wh_not_null ( $sport_search ) &&
			$club_search == 0 && $sport_search == 0 && wh_null ( $action ) )
		{
			header ( 'Location: edit.php' );
		}

		if ( $action == 'update' || ( wh_not_null ( $club_search ) && $club_search != '0' ) )
		{
			global $club;
			$club = (object) array ();
#			var_dump ( get_object_vars ($club) );
#			var_dump ($id);
			if ( $action != 'update' && wh_not_null($club_search) ) {
				$id = $club_search;
			};
#			$id = wh_not_null($club_search) ? $club_search : wh_not_null($id) ? $id : null;
#			var_dump ($club_search);
#			var_dump (wh_not_null($club_search));
#			var_dump ($id);
			if ( $id == 'null' || $id == '0' ) {
				return;
			}
			if ( $row_obj = wh_db_fetch_object_custom ( getClubById ( $id ) ) )
			{
				$club -> id = wh_db_output ( $row_obj->id );
				$club -> name = wh_db_output ( $row_obj->name );
				$club -> address = wh_db_output ( $row_obj->address );
				$club -> postcode = wh_db_output ( $row_obj->postcode );
				$club -> latitude = wh_db_output ( $row_obj->latitude );
				$club -> longtitude = wh_db_output ( $row_obj->longtitude );
				$club -> comment = wh_db_output ( $row_obj->comment );
				$club -> email = wh_db_output ( $row_obj->email );
				$club -> phone = wh_db_output ( $row_obj->phone );
				$club -> time_open = wh_db_output ( $row_obj->time_open );
				$club -> time_close = wh_db_output ( $row_obj->time_close );
				$club -> price_member = wh_db_output ( $row_obj->price_member );
				$club -> price_nonmember = wh_db_output ( $row_obj->price_nonmember );
#				var_dump ( get_object_vars ($club) );
#				$club -> sports = getSportsByClub ( $club->id );

			}
			unset ( $row_obj );
#			var_dump ( $club );
#			die();
		}
		if ( $action != 'update' && wh_not_null ( $sport_search ) && $sport_search != '0' )
		{
#			var_dump ( $sport_search );
			global $clubs;
			$clubs = array ( 0 => str_repeat ( '&nbsp', 45 ) );
			$club_query = getClubsBySportsId ( array ( $sport_search ) );
			while ( $row_obj = wh_db_fetch_object_custom ( $club_query ) ) {
				$clubs [ $row_obj->id ] = $row_obj->name;
			}
			unset ( $club_query );
			unset ($row_obj);
#			var_dump ( $clubs );
#			die ();
		}
		else
		{
			global $clubs;
			$clubs = array ( 0 => str_repeat ( '&nbsp', 45 ) );
			$club_query = getClubsOrderByName ( );
			while ( $row_obj = wh_db_fetch_object_custom ( $club_query ) ) {
				$clubs [ $row_obj->id ] = $row_obj->name;
			}
			unset ( $club_query );
			unset ($row_obj);
#			var_dump ( $clubs );
#			die ();
		}

	}

	selectClub ( );
	editClub ( );
#	var_dump (get_object_vars($club));
?>
