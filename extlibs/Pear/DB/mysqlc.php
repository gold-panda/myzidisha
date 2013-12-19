<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Stig Bakken <ssb@php.net>                                    |
// | Maintainer: Daniel Convissor <danielc@php.net>                       |
// +----------------------------------------------------------------------+
//
// $Id: mysqlc.php,v 1.3 2009/05/04 10:08:40 cvstest Exp $
//
// THis is modified and extended to have following functions check for the
//  cached files first.
//  getRow
//  getCol
//  getAll
//  getAssociated
//  getOne

// THis cache mechanism is using Delayed Connection Mechanism
//

// XXX legend:
//
// XXX ERRORMSG: The error message from the mysql function should
//               be registered here.
//
// TODO/wishlist:
// longReadlen
// binmode


require_once PEAR_DIR . 'DB/common.php';

/**
 * Database independent query interface definition for PHP's MySQL
 * extension.
 *
 * This is for MySQL versions 4.0 and below.
 *
 * @package  DB
 * @version  $Id: mysqlc.php,v 1.3 2009/05/04 10:08:40 cvstest Exp $
 * @category Database
 * @author   Stig Bakken <ssb@php.net>
 */
class DB_mysqlc extends DB_common
{
    // {{{ properties

    var $connection;
    var $phptype, $dbsyntax;
    var $prepare_tokens = array();
    var $prepare_types = array();
    var $num_rows = array();
    var $transaction_opcount = 0;
    var $autocommit = true;
    var $fetchmode = DB_FETCHMODE_ORDERED; /* Default fetch mode */
    var $_db = false;
	var $cached_tables = array();
	var $dsn = array();
	var $_dsninfo = array();
	var $_dboptions = array();
	var $_connected;
    // }}}
    // {{{ constructor

    /**
     * DB_mysql constructor.
     *
     * @access public
     */
    function DB_mysqlc()
    {
        $this->DB_common();
        $this->dbsyntax = 'mysql';
        $this->features = array(
            'prepare' => false,
            'pconnect' => true,
            'transactions' => true,
            'limit' => 'alter'
        );
        $this->errorcode_map = array(
            1004 => DB_ERROR_CANNOT_CREATE,
            1005 => DB_ERROR_CANNOT_CREATE,
            1006 => DB_ERROR_CANNOT_CREATE,
            1007 => DB_ERROR_ALREADY_EXISTS,
            1008 => DB_ERROR_CANNOT_DROP,
            1022 => DB_ERROR_ALREADY_EXISTS,
            1046 => DB_ERROR_NODBSELECTED,
            1050 => DB_ERROR_ALREADY_EXISTS,
            1051 => DB_ERROR_NOSUCHTABLE,
            1054 => DB_ERROR_NOSUCHFIELD,
            1062 => DB_ERROR_ALREADY_EXISTS,
            1064 => DB_ERROR_SYNTAX,
            1100 => DB_ERROR_NOT_LOCKED,
            1136 => DB_ERROR_VALUE_COUNT_ON_ROW,
            1146 => DB_ERROR_NOSUCHTABLE,
            1048 => DB_ERROR_CONSTRAINT,
            1216 => DB_ERROR_CONSTRAINT
        );
    }

    // }}}
    // {{{ connect()

    /**
     * Connect to a database and log in as the specified user.
     *
     * @param $dsn the data source name (see DB::parseDSN for syntax)
     * @param $persistent (optional) whether the connection should
     *        be persistent
     * @access public
     * @return int DB_OK on success, a DB error on failure
     */
//    function connect($dsninfo, $persistent = false)
    function connect()
    {
        if (!DB::assertExtension('mysql')) {
            return $this->raiseError(DB_ERROR_EXTENSION_NOT_FOUND);
        }
//        if ($dsninfo['phptype'] == 'mysqlc') $dsninfo['phptype'] = 'mysql';
        $this->dsn = $dsninfo = $this->_dsninfo;
        if ($dsninfo['protocol'] && $dsninfo['protocol'] == 'unix') {
            $dbhost = ':' . $dsninfo['socket'];
        } else {
            $dbhost = $dsninfo['hostspec'] ? $dsninfo['hostspec'] : 'localhost';
            if ($dsninfo['port']) {
                $dbhost .= ':' . $dsninfo['port'];
            }
        }

        $connect_function = $persistent ? 'mysql_pconnect' : 'mysql_connect';

        if ($dbhost && $dsninfo['username'] && isset($dsninfo['password'])) {
            $conn = @$connect_function($dbhost, $dsninfo['username'],
                                       $dsninfo['password']);
        } elseif ($dbhost && $dsninfo['username']) {
            $conn = @$connect_function($dbhost, $dsninfo['username']);
        } elseif ($dbhost) {
            $conn = @$connect_function($dbhost);
        } else {
            $conn = false;
        }
        if (!$conn) {
            if (($err = @mysql_error()) != '') {
                return $this->raiseError(DB_ERROR_CONNECT_FAILED, null, null,
                                         null, $err);
            } elseif (empty($php_errormsg)) {
                return $this->raiseError(DB_ERROR_CONNECT_FAILED);
            } else {
                return $this->raiseError(DB_ERROR_CONNECT_FAILED, null, null,
                                         null, $php_errormsg);
            }
        }

        if ($dsninfo['database']) {
            if (!@mysql_select_db($dsninfo['database'], $conn)) {
               switch(mysql_errno($conn)) {
                        case 1049:
                            return $this->raiseError(DB_ERROR_NOSUCHDB, null, null,
                                                     null, @mysql_error($conn));
                        case 1044:
                             return $this->raiseError(DB_ERROR_ACCESS_VIOLATION, null, null,
                                                      null, @mysql_error($conn));
                        default:
                            return $this->raiseError(DB_ERROR, null, null,
                                                     null, @mysql_error($conn));
                    }
            }
            // fix to allow calls to different databases in the same script
            $this->_db = $dsninfo['database'];
        }

        $this->connection = $conn;
        return DB_OK;
    }

    // }}}
    // {{{ disconnect()

    /**
     * Log out and disconnect from the database.
     *
     * @access public
     *
     * @return bool true on success, false if not connected.
     */
    function disconnect()
    {
		if ($this->_connected) {
	        $ret = @mysql_close($this->connection);
	        $this->connection = null;
	        return $ret;
		}
    }

    // }}}

    // {{{ simpleQuery()

    /**
     * Send a query to MySQL and return the results as a MySQL resource
     * identifier.
     *
     * @param the SQL query
     *
     * @access public
     *
     * @return mixed returns a valid MySQL result for successful SELECT
     * queries, DB_OK for other successful queries.  A DB error is
     * returned on failure.
     */
    function simpleQuery($query)
    {
        $ismanip = DB::isManip($query);
        $this->last_query = $query;
        $query = $this->modifyQuery($query);
		$this->dbconnect();

        if ($this->_db) {
            if (!@mysql_select_db($this->_db, $this->connection)) {
                return $this->mysqlRaiseError(DB_ERROR_NODBSELECTED);
            }
        }
        if (!$this->autocommit && $ismanip) {
            if ($this->transaction_opcount == 0) {
                $result = @mysql_query('SET AUTOCOMMIT=0', $this->connection);
                $result = @mysql_query('BEGIN', $this->connection);
                if (!$result) {
                    return $this->mysqlRaiseError();
                }
            }
            $this->transaction_opcount++;
        }
        $result = @mysql_query($query, $this->connection);
        if (!$result) {
            return $this->mysqlRaiseError();
        }
        if (is_resource($result)) {
            $numrows = $this->numrows($result);
            if (is_object($numrows)) {
                return $numrows;
            }
            $this->num_rows[(int)$result] = $numrows;
            return $result;
        }
        return DB_OK;
    }

    // }}}

    // {{{ nextResult()

    /**
     * Move the internal mysql result pointer to the next available result
     *
     * This method has not been implemented yet.
     *
     * @param a valid sql result resource
     *
     * @access public
     *
     * @return false
     */
    function nextResult($result)
    {
        return false;
    }

    // }}}
    // {{{ fetchInto()

    /**
     * Fetch a row and insert the data into an existing array.
     *
     * Formating of the array and the data therein are configurable.
     * See DB_result::fetchInto() for more information.
     *
     * @param resource $result    query result identifier
     * @param array    $arr       (reference) array where data from the row
     *                            should be placed
     * @param int      $fetchmode how the resulting array should be indexed
     * @param int      $rownum    the row number to fetch
     *
     * @return mixed DB_OK on success, null when end of result set is
     *               reached or on failure
     *
     * @see DB_result::fetchInto()
     * @access private
     */
    function fetchInto($result, &$arr, $fetchmode, $rownum=null)
    {
        if ($rownum !== null) {
            if (!@mysql_data_seek($result, $rownum)) {
                return null;
            }
        }
        if ($fetchmode & DB_FETCHMODE_ASSOC) {
            $arr = @mysql_fetch_array($result, MYSQL_ASSOC);
            if ($this->options['portability'] & DB_PORTABILITY_LOWERCASE && $arr) {
                $arr = array_change_key_case($arr, CASE_LOWER);
            }
        } else {
            $arr = @mysql_fetch_row($result);
        }
        if (!$arr) {
            // See: http://bugs.php.net/bug.php?id=22328
            // for why we can't check errors on fetching
            return null;
            /*
            $errno = @mysql_errno($this->connection);
            if (!$errno) {
                return null;
            }
            return $this->mysqlRaiseError($errno);
            */
        }
        if ($this->options['portability'] & DB_PORTABILITY_RTRIM) {
            /*
             * Even though this DBMS already trims output, we do this because
             * a field might have intentional whitespace at the end that
             * gets removed by DB_PORTABILITY_RTRIM under another driver.
             */
            $this->_rtrimArrayValues($arr);
        }
        if ($this->options['portability'] & DB_PORTABILITY_NULL_TO_EMPTY) {
            $this->_convertNullArrayValuesToEmpty($arr);
        }
        return DB_OK;
    }

    // }}}
    // {{{ freeResult()

    /**
     * Free the internal resources associated with $result.
     *
     * @param $result MySQL result identifier
     *
     * @access public
     *
     * @return bool true on success, false if $result is invalid
     */
    function freeResult($result)
    {
        unset($this->num_rows[(int)$result]);
        return @mysql_free_result($result);
    }

    // }}}
    // {{{ numCols()

    /**
     * Get the number of columns in a result set.
     *
     * @param $result MySQL result identifier
     *
     * @access public
     *
     * @return int the number of columns per row in $result
     */
    function numCols($result)
    {
        $cols = @mysql_num_fields($result);

        if (!$cols) {
            return $this->mysqlRaiseError();
        }

        return $cols;
    }

    // }}}
    // {{{ numRows()

    /**
     * Get the number of rows in a result set.
     *
     * @param $result MySQL result identifier
     *
     * @access public
     *
     * @return int the number of rows in $result
     */
    function numRows($result)
    {
        $rows = @mysql_num_rows($result);
        if ($rows === null) {
            return $this->mysqlRaiseError();
        }
        return $rows;
    }

    // }}}
    // {{{ autoCommit()

    /**
     * Enable/disable automatic commits
     */
    function autoCommit($onoff = false)
    {
        // XXX if $this->transaction_opcount > 0, we should probably
        // issue a warning here.
        $this->autocommit = $onoff ? true : false;
        return DB_OK;
    }

    // }}}
    // {{{ commit()

    /**
     * Commit the current transaction.
     */
    function commit()
    {
        if ($this->transaction_opcount > 0) {
            if ($this->_db) {
                if (!@mysql_select_db($this->_db, $this->connection)) {
                    return $this->mysqlRaiseError(DB_ERROR_NODBSELECTED);
                }
            }
            $result = @mysql_query('COMMIT', $this->connection);
            $result = @mysql_query('SET AUTOCOMMIT=1', $this->connection);
            $this->transaction_opcount = 0;
            if (!$result) {
                return $this->mysqlRaiseError();
            }
        }
        return DB_OK;
    }

    // }}}
    // {{{ rollback()

    /**
     * Roll back (undo) the current transaction.
     */
    function rollback()
    {
        if ($this->transaction_opcount > 0) {
            if ($this->_db) {
                if (!@mysql_select_db($this->_db, $this->connection)) {
                    return $this->mysqlRaiseError(DB_ERROR_NODBSELECTED);
                }
            }
            $result = @mysql_query('ROLLBACK', $this->connection);
            $result = @mysql_query('SET AUTOCOMMIT=1', $this->connection);
            $this->transaction_opcount = 0;
            if (!$result) {
                return $this->mysqlRaiseError();
            }
        }
        return DB_OK;
    }

    // }}}
    // {{{ affectedRows()

    /**
     * Gets the number of rows affected by the data manipulation
     * query.  For other queries, this function returns 0.
     *
     * @return number of rows affected by the last query
     */
    function affectedRows()
    {
        if (DB::isManip($this->last_query)) {
            return @mysql_affected_rows($this->connection);
        } else {
            return 0;
        }
     }

    // }}}
    // {{{ errorNative()

    /**
     * Get the native error code of the last error (if any) that
     * occured on the current connection.
     *
     * @access public
     *
     * @return int native MySQL error code
     */
    function errorNative()
    {
        return @mysql_errno($this->connection);
    }

    // }}}
    // {{{ nextId()

    /**
     * Returns the next free id in a sequence
     *
     * @param string  $seq_name  name of the sequence
     * @param boolean $ondemand  when true, the seqence is automatically
     *                           created if it does not exist
     *
     * @return int  the next id number in the sequence.  DB_Error if problem.
     *
     * @internal
     * @see DB_common::nextID()
     * @access public
     */
    function nextId($seq_name, $ondemand = true)
    {
        $seqname = $this->getSequenceName($seq_name);
        do {
            $repeat = 0;
            $this->pushErrorHandling(PEAR_ERROR_RETURN);
            $result = $this->query("UPDATE ${seqname} ".
                                   'SET id=LAST_INSERT_ID(id+1)');
            $this->popErrorHandling();
            if ($result === DB_OK) {
                /** COMMON CASE **/
                $id = @mysql_insert_id($this->connection);
                if ($id != 0) {
                    return $id;
                }
                /** EMPTY SEQ TABLE **/
                // Sequence table must be empty for some reason, so fill it and return 1
                // Obtain a user-level lock
                $result = $this->getOne("SELECT GET_LOCK('${seqname}_lock',10)");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                if ($result == 0) {
                    // Failed to get the lock, bail with a DB_ERROR_NOT_LOCKED error
                    return $this->mysqlRaiseError(DB_ERROR_NOT_LOCKED);
                }

                // add the default value
                $result = $this->query("REPLACE INTO ${seqname} VALUES (0)");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }

                // Release the lock
                $result = $this->getOne("SELECT RELEASE_LOCK('${seqname}_lock')");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                // We know what the result will be, so no need to try again
                return 1;

            /** ONDEMAND TABLE CREATION **/
            } elseif ($ondemand && DB::isError($result) &&
                $result->getCode() == DB_ERROR_NOSUCHTABLE)
            {
                $result = $this->createSequence($seq_name);
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                } else {
                    $repeat = 1;
                }

            /** BACKWARDS COMPAT **/
            } elseif (DB::isError($result) &&
                      $result->getCode() == DB_ERROR_ALREADY_EXISTS)
            {
                // see _BCsequence() comment
                $result = $this->_BCsequence($seqname);
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                $repeat = 1;
            }
        } while ($repeat);

        return $this->raiseError($result);
    }

    // }}}
    // {{{ createSequence()

    /**
     * Creates a new sequence
     *
     * @param string $seq_name  name of the new sequence
     *
     * @return int  DB_OK on success.  A DB_Error object is returned if
     *              problems arise.
     *
     * @internal
     * @see DB_common::createSequence()
     * @access public
     */
    function createSequence($seq_name)
    {
        $seqname = $this->getSequenceName($seq_name);
        $res = $this->query("CREATE TABLE ${seqname} ".
                            '(id INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,'.
                            ' PRIMARY KEY(id))');
        if (DB::isError($res)) {
            return $res;
        }
        // insert yields value 1, nextId call will generate ID 2
        $res = $this->query("INSERT INTO ${seqname} VALUES(0)");
        if (DB::isError($res)) {
            return $res;
        }
        // so reset to zero
        return $this->query("UPDATE ${seqname} SET id = 0;");
    }

    // }}}
    // {{{ dropSequence()

    /**
     * Deletes a sequence
     *
     * @param string $seq_name  name of the sequence to be deleted
     *
     * @return int  DB_OK on success.  DB_Error if problems.
     *
     * @internal
     * @see DB_common::dropSequence()
     * @access public
     */
    function dropSequence($seq_name)
    {
        return $this->query('DROP TABLE ' . $this->getSequenceName($seq_name));
    }

    // }}}
    // {{{ _BCsequence()

    /**
     * Backwards compatibility with old sequence emulation implementation
     * (clean up the dupes)
     *
     * @param string $seqname The sequence name to clean up
     * @return mixed DB_Error or true
     */
    function _BCsequence($seqname)
    {
        // Obtain a user-level lock... this will release any previous
        // application locks, but unlike LOCK TABLES, it does not abort
        // the current transaction and is much less frequently used.
        $result = $this->getOne("SELECT GET_LOCK('${seqname}_lock',10)");
        if (DB::isError($result)) {
            return $result;
        }
        if ($result == 0) {
            // Failed to get the lock, can't do the conversion, bail
            // with a DB_ERROR_NOT_LOCKED error
            return $this->mysqlRaiseError(DB_ERROR_NOT_LOCKED);
        }

        $highest_id = $this->getOne("SELECT MAX(id) FROM ${seqname}");
        if (DB::isError($highest_id)) {
            return $highest_id;
        }
        // This should kill all rows except the highest
        // We should probably do something if $highest_id isn't
        // numeric, but I'm at a loss as how to handle that...
        $result = $this->query("DELETE FROM ${seqname} WHERE id <> $highest_id");
        if (DB::isError($result)) {
            return $result;
        }

        // If another thread has been waiting for this lock,
        // it will go thru the above procedure, but will have no
        // real effect
        $result = $this->getOne("SELECT RELEASE_LOCK('${seqname}_lock')");
        if (DB::isError($result)) {
            return $result;
        }
        return true;
    }

    // }}}
    // {{{ quoteIdentifier()

    /**
     * Quote a string so it can be safely used as a table or column name
     *
     * Quoting style depends on which database driver is being used.
     *
     * MySQL can't handle the backtick character (<kbd>`</kbd>) in
     * table or column names.
     *
     * @param string $str  identifier name to be quoted
     *
     * @return string  quoted identifier string
     *
     * @since 1.6.0
     * @access public
     * @internal
     */
    function quoteIdentifier($str)
    {
        return '`' . $str . '`';
    }

    // }}}
    // {{{ quote()

    /**
     * @deprecated  Deprecated in release 1.6.0
     * @internal
     */
    function quote($str) {
        return $this->quoteSmart($str);
    }

    // }}}
    // {{{ escapeSimple()

    /**
     * Escape a string according to the current DBMS's standards
     *
     * @param string $str  the string to be escaped
     *
     * @return string  the escaped string
     *
     * @internal
     */
    function escapeSimple($str) {
        if (function_exists('mysql_real_escape_string')) {
            return @mysql_real_escape_string($str, $this->connection);
        } else {
            return @mysql_escape_string($str);
        }
    }

    // }}}
    // {{{ modifyQuery()

    function modifyQuery($query)
    {
        if ($this->options['portability'] & DB_PORTABILITY_DELETE_COUNT) {
            // "DELETE FROM table" gives 0 affected rows in MySQL.
            // This little hack lets you know how many rows were deleted.
            if (preg_match('/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $query)) {
                $query = preg_replace('/^\s*DELETE\s+FROM\s+(\S+)\s*$/',
                                      'DELETE FROM \1 WHERE 1=1', $query);
            }
        }
        return $query;
    }

    // }}}
    // {{{ modifyLimitQuery()

    function modifyLimitQuery($query, $from, $count, $params = array())
    {
        if (DB::isManip($query)) {
            return $query . " LIMIT $count";
        } else {
            return $query . " LIMIT $from, $count";
        }
    }

    // }}}
    // {{{ mysqlRaiseError()

    /**
     * Gather information about an error, then use that info to create a
     * DB error object and finally return that object.
     *
     * @param  integer  $errno  PEAR error number (usually a DB constant) if
     *                          manually raising an error
     * @return object  DB error object
     * @see DB_common::errorCode()
     * @see DB_common::raiseError()
     */
    function mysqlRaiseError($errno = null)
    {
        if ($errno === null) {
            if ($this->options['portability'] & DB_PORTABILITY_ERRORS) {
                $this->errorcode_map[1022] = DB_ERROR_CONSTRAINT;
                $this->errorcode_map[1048] = DB_ERROR_CONSTRAINT_NOT_NULL;
                $this->errorcode_map[1062] = DB_ERROR_CONSTRAINT;
            } else {
                // Doing this in case mode changes during runtime.
                $this->errorcode_map[1022] = DB_ERROR_ALREADY_EXISTS;
                $this->errorcode_map[1048] = DB_ERROR_CONSTRAINT;
                $this->errorcode_map[1062] = DB_ERROR_ALREADY_EXISTS;
            }
            $errno = $this->errorCode(mysql_errno($this->connection));
        }
        return $this->raiseError($errno, null, null, null,
                                 @mysql_errno($this->connection) . ' ** ' .
                                 @mysql_error($this->connection));
    }

    // }}}
    // {{{ tableInfo()

    /**
     * Returns information about a table or a result set.
     *
     * @param object|string  $result  DB_result object from a query or a
     *                                string containing the name of a table
     * @param int            $mode    a valid tableInfo mode
     * @return array  an associative array with the information requested
     *                or an error object if something is wrong
     * @access public
     * @internal
     * @see DB_common::tableInfo()
     */
    function tableInfo($result, $mode = null) {
        if (isset($result->result)) {
            /*
             * Probably received a result object.
             * Extract the result resource identifier.
             */
            $id = $result->result;
            $got_string = false;
        } elseif (is_string($result)) {
            /*
             * Probably received a table name.
             * Create a result resource identifier.
             */
            $id = @mysql_list_fields($this->dsn['database'],
                                     $result, $this->connection);
            $got_string = true;
        } else {
            /*
             * Probably received a result resource identifier.
             * Copy it.
             * Deprecated.  Here for compatibility only.
             */
            $id = $result;
            $got_string = false;
        }

        if (!is_resource($id)) {
            return $this->mysqlRaiseError(DB_ERROR_NEED_MORE_DATA);
        }

        if ($this->options['portability'] & DB_PORTABILITY_LOWERCASE) {
            $case_func = 'strtolower';
        } else {
            $case_func = 'strval';
        }

        $count = @mysql_num_fields($id);

        // made this IF due to performance (one if is faster than $count if's)
        if (!$mode) {
            for ($i=0; $i<$count; $i++) {
                $res[$i]['table'] = $case_func(@mysql_field_table($id, $i));
                $res[$i]['name']  = $case_func(@mysql_field_name($id, $i));
                $res[$i]['type']  = @mysql_field_type($id, $i);
                $res[$i]['len']   = @mysql_field_len($id, $i);
                $res[$i]['flags'] = @mysql_field_flags($id, $i);
            }
        } else { // full
            $res['num_fields']= $count;

            for ($i=0; $i<$count; $i++) {
                $res[$i]['table'] = $case_func(@mysql_field_table($id, $i));
                $res[$i]['name']  = $case_func(@mysql_field_name($id, $i));
                $res[$i]['type']  = @mysql_field_type($id, $i);
                $res[$i]['len']   = @mysql_field_len($id, $i);
                $res[$i]['flags'] = @mysql_field_flags($id, $i);

                if ($mode & DB_TABLEINFO_ORDER) {
                    $res['order'][$res[$i]['name']] = $i;
                }
                if ($mode & DB_TABLEINFO_ORDERTABLE) {
                    $res['ordertable'][$res[$i]['table']][$res[$i]['name']] = $i;
                }
            }
        }

        // free the result only if we were called on a table
        if ($got_string) {
            @mysql_free_result($id);
        }
        return $res;
    }

    // }}}
    // {{{ getSpecialQuery()

    /**
     * Returns the query needed to get some backend info
     * @param string $type What kind of info you want to retrieve
     * @return string The SQL query string
     */
    function getSpecialQuery($type)
    {
        switch ($type) {
            case 'tables':
                return 'SHOW TABLES';
            case 'views':
                return DB_ERROR_NOT_CAPABLE;
            case 'users':
                $sql = 'select distinct User from user';
                if ($this->dsn['database'] != 'mysql') {
                    $dsn = $this->dsn;
                    $dsn['database'] = 'mysql';
                    if (DB::isError($db = DB::connect($dsn))) {
                        return $db;
                    }
                    $sql = $db->getCol($sql);
                    $db->disconnect();
                    // XXX Fixme the mysql driver should take care of this
                    if (!@mysql_select_db($this->dsn['database'], $this->connection)) {
                        return $this->mysqlRaiseError(DB_ERROR_NODBSELECTED);
                    }
                }
                return $sql;
            case 'databases':
                return 'SHOW DATABASES';
            default:
                return null;
        }
    }

    // }}}

	/* Following functions are redefining of functions available in DB_common.
	 * redefined here to enable caching mechanism
	 * Vijay Nair
	 */

    // {{{ getOne()

    /**
     * Fetch the first column of the first row of data returned from
     * a query
     *
     * Takes care of doing the query and freeing the results when finished.
     *
     * @param string $query  the SQL query
     * @param mixed  $params array, string or numeric data to be used in
     *                       execution of the statement.  Quantity of items
     *                       passed must match quantity of placeholders in
     *                       query:  meaning 1 placeholder for non-array
     *                       parameters or 1 placeholder per array element.
     *
     * @return mixed  the returned value of the query.  DB_Error on failure.
     *
     * @access public
     */
    function &getOne($query, $params = array())
    {
        settype($params, 'array');
		$qry = $this->prepareFullQuery($query, $params);

		$cached_data = $this->checkCache($qry);
		if ($cached_data ) {
			$val = $cached_data;
			if (is_array($val)) {
				$val = $val[0];
			}
			return $val;
		}
        if (sizeof($params) > 0) {
            $sth = $this->prepare($query);
            if (DB::isError($sth)) {
                return $sth;
            }
            $res =& $this->execute($sth, $params);
            $this->freePrepared($sth);
        } else {
            $res =& $this->query($query);
        }

        if (DB::isError($res)) {
            return $res;
        }

        $err = $res->fetchInto($row, DB_FETCHMODE_ORDERED);
        $res->free();

        if ($err !== DB_OK) {
            return $err;
        }
		$val = $row[0];

		if (is_array($val) ){
			$val = array_values($val);
		}

		$this->saveCache($qry, $val);

        return $val;
    }

    // }}}
    // {{{ getRow()

    /**
     * Fetch the first row of data returned from a query
     *
     * Takes care of doing the query and freeing the results when finished.
     *
     * @param string $query  the SQL query
     * @param array  $params array to be used in execution of the statement.
     *                       Quantity of array elements must match quantity
     *                       of placeholders in query.  This function does
     *                       NOT support scalars.
     * @param int    $fetchmode  the fetch mode to use
     *
     * @return array the first row of results as an array indexed from
     *               0, or a DB error code.
     *
     * @access public
     */
    function &getRow($query,
                     $params = array(),
                     $fetchmode = DB_FETCHMODE_DEFAULT)
    {
        // compat check, the params and fetchmode parameters used to
        // have the opposite order
        settype($params, 'array');
		$qry = $this->prepareFullQuery($query, $params);
		$cached_data = $this->checkCache($qry);
		if ($cached_data ) {
			return $cached_data;
		}

        if (!is_array($params)) {
            if (is_array($fetchmode)) {
                if ($params === null) {
                    $tmp = DB_FETCHMODE_DEFAULT;
                } else {
                    $tmp = $params;
                }
                $params = $fetchmode;
                $fetchmode = $tmp;
            } elseif ($params !== null) {
                $fetchmode = $params;
                $params = array();
            }
        }

        if (sizeof($params) > 0) {
            $sth = $this->prepare($query);
            if (DB::isError($sth)) {
                return $sth;
            }
            $res =& $this->execute($sth, $params);
            $this->freePrepared($sth);
        } else {
            $res =& $this->query($query);
        }

        if (DB::isError($res)) {
            return $res;
        }

        $err = $res->fetchInto($row, $fetchmode);

        $res->free();

        if ($err !== DB_OK) {
            return $err;
        }

		$this->saveCache($qry, $row);

        return $row;
    }

    // }}}
    // {{{ getCol()

    /**
     * Fetch a single column from a result set and return it as an
     * indexed array
     *
     * @param string $query  the SQL query
     * @param mixed  $col    which column to return (integer [column number,
     *                       starting at 0] or string [column name])
     * @param mixed  $params array, string or numeric data to be used in
     *                       execution of the statement.  Quantity of items
     *                       passed must match quantity of placeholders in
     *                       query:  meaning 1 placeholder for non-array
     *                       parameters or 1 placeholder per array element.
     *
     * @return array  an indexed array with the data from the first
     *                row at index 0, or a DB error code
     *
     * @see DB_common::query()
     * @access public
     */
    function &getCol($query, $col = 0, $params = array())
    {
        settype($params, 'array');
		$qry = $this->prepareFullQuery($query, $params);
		$cached_data = $this->checkCache($qry);

		if ($cached_data ) {
			return $cached_data;
		}

        if (sizeof($params) > 0) {
            $sth = $this->prepare($query);

            if (DB::isError($sth)) {
                return $sth;
            }

            $res =& $this->execute($sth, $params);
            $this->freePrepared($sth);
        } else {
            $res =& $this->query($query);
        }

        if (DB::isError($res)) {
            return $res;
        }

        $fetchmode = is_int($col) ? DB_FETCHMODE_ORDERED : DB_FETCHMODE_ASSOC;
        $ret = array();

        while (is_array($row = $res->fetchRow($fetchmode))) {
            $ret[] = $row[$col];
        }

        $res->free();

        if (DB::isError($row)) {
            $ret = $row;
        }
		$this->saveCache($qry, $ret);

        return $ret;
    }

    // }}}
    // {{{ getAssoc()

    /**
     * Fetch the entire result set of a query and return it as an
     * associative array using the first column as the key
     *
     * If the result set contains more than two columns, the value
     * will be an array of the values from column 2-n.  If the result
     * set contains only two columns, the returned value will be a
     * scalar with the value of the second column (unless forced to an
     * array with the $force_array parameter).  A DB error code is
     * returned on errors.  If the result set contains fewer than two
     * columns, a DB_ERROR_TRUNCATED error is returned.
     *
     * For example, if the table "mytable" contains:
     *
     * <pre>
     *  ID      TEXT       DATE
     * --------------------------------
     *  1       'one'      944679408
     *  2       'two'      944679408
     *  3       'three'    944679408
     * </pre>
     *
     * Then the call getAssoc('SELECT id,text FROM mytable') returns:
     * <pre>
     *   array(
     *     '1' => 'one',
     *     '2' => 'two',
     *     '3' => 'three',
     *   )
     * </pre>
     *
     * ...while the call getAssoc('SELECT id,text,date FROM mytable') returns:
     * <pre>
     *   array(
     *     '1' => array('one', '944679408'),
     *     '2' => array('two', '944679408'),
     *     '3' => array('three', '944679408')
     *   )
     * </pre>
     *
     * If the more than one row occurs with the same value in the
     * first column, the last row overwrites all previous ones by
     * default.  Use the $group parameter if you don't want to
     * overwrite like this.  Example:
     *
     * <pre>
     * getAssoc('SELECT category,id,name FROM mytable', false, null,
     *          DB_FETCHMODE_ASSOC, true) returns:
     *
     *   array(
     *     '1' => array(array('id' => '4', 'name' => 'number four'),
     *                  array('id' => '6', 'name' => 'number six')
     *            ),
     *     '9' => array(array('id' => '4', 'name' => 'number four'),
     *                  array('id' => '6', 'name' => 'number six')
     *            )
     *   )
     * </pre>
     *
     * Keep in mind that database functions in PHP usually return string
     * values for results regardless of the database's internal type.
     *
     * @param string  $query  the SQL query
     * @param boolean $force_array  used only when the query returns
     *                              exactly two columns.  If true, the values
     *                              of the returned array will be one-element
     *                              arrays instead of scalars.
     * @param mixed   $params array, string or numeric data to be used in
     *                        execution of the statement.  Quantity of items
     *                        passed must match quantity of placeholders in
     *                        query:  meaning 1 placeholder for non-array
     *                        parameters or 1 placeholder per array element.
     * @param boolean $group  if true, the values of the returned array
     *                        is wrapped in another array.  If the same
     *                        key value (in the first column) repeats
     *                        itself, the values will be appended to
     *                        this array instead of overwriting the
     *                        existing values.
     *
     * @return array  associative array with results from the query.
     *                DB Error on failure.
     *
     * @access public
     */
    function &getAssoc($query, $force_array = false, $params = array(),
                       $fetchmode = DB_FETCHMODE_DEFAULT, $group = false)
    {
        settype($params, 'array');
		$qry = $this->prepareFullQuery($query, $params);
		$cached_data = $this->checkCache($qry);

		if ($cached_data ) {
			return $cached_data;
		}

        if (sizeof($params) > 0) {
            $sth = $this->prepare($query);

            if (DB::isError($sth)) {
                return $sth;
            }

            $res =& $this->execute($sth, $params);
            $this->freePrepared($sth);
        } else {
            $res =& $this->query($query);
        }

        if (DB::isError($res)) {
            return $res;
        }
        if ($fetchmode == DB_FETCHMODE_DEFAULT) {
            $fetchmode = $this->fetchmode;
        }
        $cols = $res->numCols();

        if ($cols < 2) {
            $tmp =& $this->raiseError(DB_ERROR_TRUNCATED);
            return $tmp;
        }

        $results = array();

        if ($cols > 2 || $force_array) {
            // return array values
            // XXX this part can be optimized
            if ($fetchmode == DB_FETCHMODE_ASSOC) {
                while (is_array($row = $res->fetchRow(DB_FETCHMODE_ASSOC))) {
                    reset($row);
                    $key = current($row);
                    unset($row[key($row)]);
                    if ($group) {
                        $results[$key][] = $row;
                    } else {
                        $results[$key] = $row;
                    }
                }
            } elseif ($fetchmode == DB_FETCHMODE_OBJECT) {
                while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)) {
                    $arr = get_object_vars($row);
                    $key = current($arr);
                    if ($group) {
                        $results[$key][] = $row;
                    } else {
                        $results[$key] = $row;
                    }
                }
            } else {
                while (is_array($row = $res->fetchRow(DB_FETCHMODE_ORDERED))) {
                    // we shift away the first element to get
                    // indices running from 0 again
                    $key = array_shift($row);
                    if ($group) {
                        $results[$key][] = $row;
                    } else {
                        $results[$key] = $row;
                    }
                }
            }
            if (DB::isError($row)) {
                $results = $row;
            }
        } else {
            // return scalar values
            while (is_array($row = $res->fetchRow(DB_FETCHMODE_ORDERED))) {
                if ($group) {
                    $results[$row[0]][] = $row[1];
                } else {
                    $results[$row[0]] = $row[1];
                }
            }
            if (DB::isError($row)) {
                $results = $row;
            }
        }

        $res->free();
		$this->saveCache($qry, $results);

        return $results;
    }

    // }}}
    // {{{ getAll()

    /**
     * Fetch all the rows returned from a query
     *
     * @param string $query  the SQL query
     * @param array  $params array to be used in execution of the statement.
     *                       Quantity of array elements must match quantity
     *                       of placeholders in query.  This function does
     *                       NOT support scalars.
     * @param int    $fetchmode  the fetch mode to use
     *
     * @return array  an nested array.  DB error on failure.
     *
     * @access public
     */
    function &getAll($query,
                     $params = array(),
                     $fetchmode = DB_FETCHMODE_DEFAULT)
    {
		$qry = $this->prepareFullQuery($query, $params);
		$cached_data = $this->checkCache($qry);

		if ($cached_data ) {

			return $cached_data;
		}

        // compat check, the params and fetchmode parameters used to
        // have the opposite order
        if (!is_array($params)) {
            if (is_array($fetchmode)) {
                if ($params === null) {
                    $tmp = DB_FETCHMODE_DEFAULT;
                } else {
                    $tmp = $params;
                }
                $params = $fetchmode;
                $fetchmode = $tmp;
            } elseif ($params !== null) {
                $fetchmode = $params;
                $params = array();
            }
        }

        if (sizeof($params) > 0) {
            $sth = $this->prepare($query);

            if (DB::isError($sth)) {
                return $sth;
            }

            $res =& $this->execute($sth, $params);
            $this->freePrepared($sth);
        } else {
            $res =& $this->query($query);
        }

        if (DB::isError($res) || $res === DB_OK) {
            return $res;
        }

        $results = array();
        while (DB_OK === $res->fetchInto($row, $fetchmode)) {
            if ($fetchmode & DB_FETCHMODE_FLIPPED) {
                foreach ($row as $key => $val) {
                    $results[$key][] = $val;
                }
            } else {
                $results[] = $row;
            }
        }

        $res->free();

        if (DB::isError($row)) {
            $tmp =& $this->raiseError($row);
            return $tmp;
        }

		$this->saveCache($qry, $results);
        return $results;
    }

    // }}}

	/* Query is processed only for the update/delete/insert etc. queries.
		THis is needed to update the corresponding tables fully.

	 */
    // {{{ query()

    /**
     * Send a query to the database and return any results with a
     * DB_result object
     *
     * The query string can be either a normal statement to be sent directly
     * to the server OR if <var>$params</var> are passed the query can have
     * placeholders and it will be passed through prepare() and execute().
     *
     * @param string $query  the SQL query or the statement to prepare
     * @param mixed  $params array, string or numeric data to be used in
     *                       execution of the statement.  Quantity of items
     *                       passed must match quantity of placeholders in
     *                       query:  meaning 1 placeholder for non-array
     *                       parameters or 1 placeholder per array element.
     *
     * @return mixed  a DB_result object or DB_OK on success, a DB
     *                error on failure
     *
     * @see DB_result, DB_common::prepare(), DB_common::execute()
     * @access public
     */
    function &query($query, $params = array())
    {
		settype($params,'array');
		$qry = $this->prepareFullQuery($query, $params);
		$ismanip = $this->queryisManip($qry);
		if ($ismanip) {
			$this->updateTableTimes($qry);
		}

        if (sizeof($params) > 0) {
            $sth = $this->prepare($query);
            if (DB::isError($sth)) {
                return $sth;
            }
            $ret =& $this->execute($sth, $params);
            $this->freePrepared($sth);
            return $ret;
        } else {
            $result = $this->simpleQuery($query);
            if (DB::isError($result) || $result === DB_OK) {
                return $result;
            } else {
                $tmp =& new DB_result($this, $result);
                return $tmp;
            }
        }
    }

    // }}}

    // {{{ autoExecute()

    /**
     * Automaticaly generate an insert or update query and call prepare()
     * and execute() with it
     *
     * @param string $table name of the table
     * @param array $fields_values assoc ($key=>$value) where $key is a field name and $value its value
     * @param int $mode type of query to make (DB_AUTOQUERY_INSERT or DB_AUTOQUERY_UPDATE)
     * @param string $where in case of update queries, this string will be put after the sql WHERE statement
     * @return mixed  a new DB_Result or a DB_Error when fail
     * @see DB_common::autoPrepare(), DB_common::buildManipSQL()
     * @access public
     */
    function autoExecute($table, $fields_values, $mode = DB_AUTOQUERY_INSERT, $where = false)
    {
        $sth = $this->autoPrepare($table, array_keys($fields_values), $mode, $where);
        $ret =& $this->execute($sth, array_values($fields_values));
        $this->freePrepared($sth);
        return $ret;

    }

    // }}}

    // {{{ execute()

    /**
     * Executes a DB statement prepared with prepare()
     *
     * Example 1.
     * <code> <?php
     * $sth = $dbh->prepare('INSERT INTO tbl (a, b, c) VALUES (?, !, &)');
     * $data = array(
     *     "John's text",
     *     "'it''s good'",
     *     'filename.txt'
     * );
     * $res =& $dbh->execute($sth, $data);
     * ?></code>
     *
     * @param resource  $stmt  a DB statement resource returned from prepare()
     * @param mixed  $data  array, string or numeric data to be used in
     *                      execution of the statement.  Quantity of items
     *                      passed must match quantity of placeholders in
     *                      query:  meaning 1 placeholder for non-array
     *                      parameters or 1 placeholder per array element.
     *
     * @return object  a new DB_Result or a DB_Error when fail
     *
     * {@internal ibase and oci8 have their own execute() methods.}}
     *
     * @see DB_common::prepare()
     * @access public
     */
    function &execute($stmt, $data = array())
    {

        $realquery = $this->executeEmulateQuery($stmt, $data);

        if (DB::isError($realquery)) {
            return $realquery;
        }
		$ismanip = $this->queryisManip($realquery);
		if ($ismanip) {
			$this->updateTableTimes($realquery);
		}

        $result = $this->simpleQuery($realquery);

        if (DB::isError($result) || $result === DB_OK) {
            return $result;
        } else {
            $tmp =& new DB_result($this, $result);
            return $tmp;
        }

	}

    // }}}

    /* Cache related functions are added below */

	/* Get all tables in osDate and populate the $osDate_tables array. THis will
	   check if the file is available in cache and yes, then load the contents.
	   Otherwise get all table names from DB and create file without any time and
	   store the names in the $cached_tables array with 0 as time. */

	/* This function just does the prepare and emulateExecuteQuery and returns
		complete query with all replaceable values replaced */
	function prepareFullQuery($query, $params = array()) {
        if (sizeof($params) > 0) {
            $sth = $this->prepare($query);

            if (DB::isError($sth)) {
                return $sth;
            }
	        $realquery = $this->executeEmulateQuery($sth, $params);

			return $realquery;
		}
		return $query;
	}

	function getAllCachedTables() {
		global $config;
		if ($config['disable_cache'] == 'Y' || $config['disable_cache'] == '1' ) {
			return true;
		}
		if (is_readable(CACHE_DIR.'cached_tables.dat')  ) {
			/* OK. The cache file is available. Load it to memory */
			$this->cached_tables = unserialize( file_get_contents( CACHE_DIR.'cached_tables.dat' ) );
		} else {
			$res = $this->simpleQuery("show tables");
	        while (DB_OK === $this->fetchInto($res, $row, $fetchmode)) {
				foreach ($row as $v) {
					$this->cached_tables[$v] = 0;
				}
			}
			$this->writeCachedTables();
		}
	}

	/* This function will write the cacehd tables update time into the file */
	function writeCachedTables() {
		global $config;
		if ($config['disable_cache'] == 'Y' || $config['disable_cache'] == '1' ) {
			return true;
		}
		/* Now write this to cached_tables info file */
		$fp = fopen(CACHE_DIR.'cached_tables.dat', 'wb');
		fwrite($fp, serialize($this->cached_tables));
		fclose($fp);
	}

	// {{{ update_table_times()
	/*
	 *	This function will update the cached_tables file with latest update time.
	 *  This will write the file with updated time
	 *  @param the SQL Query
	 *
	 *	@access internal
	 *  @return none
	 */
	function updateTableTimes($query) {
		global $config;
		if ($config['disable_cache'] == 'Y' || $config['disable_cache'] == '1' ) {
			return true;
		}
		$this->getAllCachedTables();
		$query = strtolower($query);
		$table_found=0;
		$tim = (float) $this->get_micro_time1();
		if (count($this->cached_tables) > 0) {
			foreach ($this->cached_tables as $tab => $tm) {
				if (substr_count($query,strtolower($tab)) > 0) {
					$table_found++;
					$this->cached_tables[$tab]= (float) $tim+2;
				}
			}
		}
		if ($table_found == 0) {
			/* This is table manipulation query, but table name missing in the array.
				Need to add the new table */
			$res = $this->simpleQuery("show tables");

	        while (DB_OK === $this->fetchInto($res, $row, $fetchmode)) {
				foreach ($row as $v) {
					if (!isset($this->cached_tables[$v] ) ) {
						$this->cached_tables[$v] = 0;
					}
				}
			}
		}

		$this->writeCachedTables();
	}

	// }}}

	//  {{{ checkCache()
	/*
	 *	This creates a hash of the query and checks if this hash is already saved. If yes,
	 *	it takes the cached time. THen it loads the cached_tables hash and
	 *	checks the tables in the query and determine the latest upate time for the
	 *	table as given in the cached_tables hash is later than cached_time. If later, it
	 *	proceeds with query and saves the result as hash. Otherwise, it takes the cached
	 * data and returns
	 */
	 function checkCache($query)
	 {
		global $config;
		if ($config['disable_cache'] == 'Y' || $config['disable_cache'] == '1' || (isset($_SESSION['AdminId']) && $_SESSION['AdminId'] > 0 )) {
		/* Admin. should read data in any case, bypass cache */
			return false;
		}
		/* For shoutbox, no cache file checkng.. */
		if (substr_count(strtolower($query),strtolower(SHOUTBOX_TABLE)) > 0 | substr_count(strtolower($query),strtolower(INSTANT_MESSAGE_TABLE)) > 0 || substr_count(strtolower($query),strtolower(BANNER_TABLE)) > 0) {
			return false;
		}
		$ismanip = $this->queryisManip($query);
		if ($ismanip) {
			$this->updateTableTimes($query);
			return false;
		}
		/* First get all tables from the cached_tables hash */
		$this->getAllCachedTables();
		/* Get hash file name for the query */
		$cached_file_name = $this->generateCacheFilename($query);
	 	/* Now see if there is a hash for current query is svailable */
		$cached_data = $this->getCachedData($cached_file_name);

		if (!$cached_data || !is_array($cached_data) || empty($cached_data)) { return false; }
		$cached_time = $cached_data['cached_time'];
		$query = strtolower($query);
		if (count($this->cached_tables) > 0) {
			foreach ($this->cached_tables as $tab => $tm) {
				if (substr_count($query,strtolower($tab)) > 0) {
					if ($cached_time < $tm) {
						$this->removeCacheFile($cached_file_name);
						return false;
					}
				}
			}
		}
		/* Cache is valid. return data */
		return $cached_data['saved_data'];
	 }
	 // }}}

	// {{{ saveCache()
	/*
	 *	This function will update the cached_tables file with latest update time.
	 *  This will write the file with updated time
	 *  @param the SQL Query, result of query
	 *
	 *	@access internal
	 *  @return none
	 */
	function saveCache($query, $result) {
		global $config;
		if ($config['disable_cache'] == 'Y' || $config['disable_cache'] == '1' || $_SESSION['AdminId'] > 0) {
			return true;
		}
		/* For shoutbox, no cache file checkng.. */
		if (substr_count(strtolower($query),strtolower(SHOUTBOX_TABLE)) > 0 || substr_count(strtolower($query),strtolower(INSTANT_MESSAGE_TABLE)) > 0 || substr_count(strtolower($query),strtolower(BANNER_TABLE)) > 0) {
			return true;
		}

		if ((!is_array($result) && $result != '') or( is_array($result) && count($result) > 0) ){
			$cache_file = $this->generateCacheFilename($query);
			$save_array = serialize(array(
				'cached_time' => $this->get_micro_time1(),
				'saved_data' => $result)
				);

			$fp = @fopen(CACHE_DIR.$cache_file,'wb');
			@flock($fp,LOCK_EX);
			@fwrite($fp,$save_array);
			@flock($fp,LOCK_UN);
			@fclose($fp);
		}
	}

	// }}}

	/* This function generates the file name for the cached item */
	function generateCacheFilename($input)
	{
		return 'cache_'.md5($input).".dat";
	}

	/* Get microtime as table update time and cache time */
	function get_micro_time1()
	{
/*		list($usec, $sec) = explode(" ", microtime());
		return (float)($usec + $sec);
		*/
		return time();
	}

	/* This function gets the cache file for a given cached_file_name  and returns
		data, and time of cache
	*/
	function getCachedData($cached_file_name){
		global $config;
		$cached_data = array();
		if ($config['disable_cache'] == 'Y' || $config['disable_cache'] == '1' || (isset($_SESSION['AdminId']) && $_SESSION['AdminId'] > 0 ) ) {
			return $cached_data;
		}
		if (file_exists(CACHE_DIR.$cached_file_name)) {
			$cached_data = unserialize(file_get_contents(CACHE_DIR.$cached_file_name));
		}
		return $cached_data;
	}

	/* This function will remove the cacehd file from CACHE_DIR */
	function removeCacheFile($cache_file_name) {

		unlink(CACHE_DIR.$cache_file_name);
	}
    /**
     * Tell whether a query is a data manipulation query (insert,
     * update or delete) or a data definition query (create, drop,
     * alter, grant, revoke).
     *
     * @access public
     *
     * @param string $query the query
     *
     * @return boolean whether $query is a data manipulation query
     */
    function queryisManip($query)
    {
        $manips = 'INSERT|UPDATE|DELETE|';
        if (preg_match('/^\s*"?('.$manips.')\s+/i', strtoupper($query))) {
            return true;
        }
        return false;
    }

	function dbconnect() {

		if (!$this->_connected) {
			$ret = $this->connect();
			$this->_connected=true;
			return $ret;
		}
	}
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */

?>
