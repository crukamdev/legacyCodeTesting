<?php

// iterator_class.inc

// a series of objects using the Iterator pattern from the Gang Of Four's
// "Design Objects: Elements of Reusable Object-Oriented Software"

// If unfamiliar with this essential reference guide to OOP, ask Chris 
// and he will be happy to speak about it ad naseum

class MySQLResultIterator 
{
	var $result;
	
	function MySQLResultIterator(&$result)
	{
		$this->result=&$result;
	}
	
	function fetch_row()
	{
		return mysql_fetch_row($this->result);
	}
	
	function fetch_object()
	{
		return mysql_fetch_object($this->result);
	}
}

class PostgreSQLResultIterator
{
	var $result;

	function PostgreSQLResultIterator(&$result)
	{
		$this->result=&$result;
	}

	function fetch_row()
	{
		return pg_fetch_row($this->result);
	}
}

?>
