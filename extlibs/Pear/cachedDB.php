<?php
require_once PEAR_DIR . 'DB.php';

class cachedDB
{

    function &connect($dsn, $options = array())
    {

        $dsninfo = DB::parseDSN($dsn);
        $type = $dsninfo['phptype'];
		@$obj = DB::factory($type, $options);
		$obj->_dsninfo = $dsninfo;
		$obj->_dboptions = $options;

        return $obj;

    }

    // }}}

}

?>
