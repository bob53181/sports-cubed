<?php

//Die with error
	function wh_error ( $error )
	{
		die('<font color="#ff0000"><strong>' . $error . '<br /><br /><small><font color="#ff0000">[WHALE STOP]</font></small><br /><br /></strong></font>');
	}

//Check if variable is null, alias to wh_not_null with the opposite value
	function wh_null ( $arg )
	{
		return ( ! wh_not_null ( $arg ) );
	}

//Define a constant
	function wh_define ( $name, $value )
	{
		if ( defined ( $name ) )
			return;
		define ( $name, $value );
	}

//Check if constant is defined and not null
	function wh_defined ( $name )
	{
		return defined ( $name ) && wh_not_null ( constant ( $name ) );
	}

//Get value or false
	function wh_value ( $var )
	{
		if ( ! is_null ( $var ) && wh_not_null ( $var ) ) {
			return $var;
		}
		return;
	}

//Return the name and value of variable for associative array or nothing
function all_or_nothing ( $arg )
{
	if ( $arg )
	{
		return "'{$arg}' => {$$arg}";
	}
	return;
}
?>
