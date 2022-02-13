<?Php

// db_access.inc

// Database abstraction layer for sexsearch.com

// v1.0 started April 16, 2003
// Chris Hartjes

// New object, will replace DB_Instance going forward

//include_once("global_config.inc");

// Currently configured to work with new PHP/MySQL setup where the PHP "user"
// is a trusted access source to the database

class DB_Access 
{
	var $db_server;
	var $db_handle;
	var $db_name;
	var $sql;
	var $login;
	var $passwd;
	var $result;

	function Choose_DB($db_name)
	{
		if (!$this->db_name=pg_connect("host=phantasm.ibl.org port=5432 dbname=ibl_stats user=chartjes password="))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function Run_Query($sql)
	{
		$this->result=pg_exec($sql);

		return $this->result;
	}
}

?>
