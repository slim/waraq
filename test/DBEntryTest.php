<?php
	require_once 'PHPUnit.php';

class DBEntryTest extends PHPUnit_TestCase 
{
	var $DBEntry;
    function DBEntryTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

	function setUp()
	{
        require_once '../lib/dbentry.php';
		$this->DBEntry =& new DBEntry;
	}
    function tearDown()
    {
        unset($this->DBEntry);
    }

	function test_extract()
	{
		$values = array(
			'table1.col1' => '11',
			'table1.col2' => '12',
			'table2.col1' => '21'
		);

		$entries = DBEntry::extract($values);
        $this->assertEquals(2, count($entries));
        $this->assertTrue($entries[0] instanceof DBEntry);
	}
	function test_toSQLinsert()
	{
		$this->DBEntry->table = 'table1';
		$this->DBEntry->values = array('col1' => '1', 'col2' => '2');
		$expected = "INSERT INTO table1 (col1, col2) VALUES ('1','2')";
		$this->assertEquals($expected, $this->DBEntry->toSQLinsert());
	}
}
