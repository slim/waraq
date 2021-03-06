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
		$this->DBEntry =& new DBEntry('table1');
	}
    function tearDown()
    {
        unset($this->DBEntry);
    }

	function test_extract()
	{
		$values = array(
			'table1:col1' => '11',
			'table1:col2' => '12',
			'table2:col1' => '21'
		);

		$entries = DBEntry::extract($values);
        $this->assertEquals(2, count($entries));
        $this->assertTrue($entries['table1'] instanceof DBEntry, "table1 element is a DBEntry");
        $this->assertTrue($entries['table2'] instanceof DBEntry, "table2 element is a DBEntry");
	}
	function test_toSQLinsert()
	{
		$this->DBEntry->values = array('col1' => '1', 'col2' => '2');
		$expected = "INSERT INTO table1 (col1,col2) VALUES ('1','2')";
		$this->assertEquals($expected, $this->DBEntry->toSQLinsert());
	}
	function test_setProperties()
	{
		$object = new StdClass;
		$this->DBEntry->values = array('col1' => '1', 'col2' => '2');
		$this->DBEntry->setProperties($object);
		$this->assertEquals(1, $object->col1);
		$this->assertEquals(2, $object->col2);
	}
	function test_getProperties()
	{
		$object = new StdClass;
		$object->col1 = 1;
		$object->col2 = 2;
		$this->DBEntry->getProperties($object);
        $this->assertEquals(2, count($this->DBEntry->values));
		$this->assertEquals(1, $this->DBEntry->values['col1']);
		$this->assertEquals(2, $this->DBEntry->values['col2']);
	}
	function test_extract_table()
	{
		$table = array(
			array('col1' => '11', 'col2' => '12'),
			array('col1' => '21', 'col2' => '22')
		);

		$entries = DBEntry::extract_table($table, 'table1');
        $this->assertEquals(2, count($entries), "entries count");
        $this->assertTrue($entries[0] instanceof DBEntry, "first element is a DBEntry");
        $this->assertEquals('table1', $entries[0]->table, "entries table name");
		$this->assertEquals(11, $entries[0]->values['col1'], "entry 0 col 1");
		$this->assertEquals(12, $entries[0]->values['col2'], "entry 0 col 2");
	}
}
// Running the test.
$suite  = new PHPUnit_TestSuite('DBEntryTest');
$result = PHPUnit::run($suite);
echo $result->toHtml();
