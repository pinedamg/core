<?php

use erdiko\core\cache\Memcached;
require_once dirname(__DIR__).'/ErdikoTestCase.php';


class MemcacheTest extends ErdikoTestCase
{
    var $memcacheObj;

    function setUp() {
        $this->memcacheObj = new Memcached;
    }

    function tearDown() {
        unset($this->memcacheObj);
    }
	
	function testHas()
	{	
		/**
		 *	Precondition
		 *
		 *  Check if there is nothing
		 */

		$key = 'Test_Key';
		$data = 'Test_Data';
		$return =  $this->memcacheObj->has($key);
		$this->assertFalse($return);

		//Add a data
		$this->memcacheObj->put($key, $data);

		//Check if the data exists
		$return = $this->memcacheObj->has($key);
		$this->assertTrue($return);
	}

	/**
	 *
	 *	@depends testHas
	 *
	 */
    function testPutAndGet()
	{
		/**
		 *	Precondition
		 *
		 *  Check if there is nothing
		 */
		$this->memcacheObj->forgetAll();
		$key = 'stringTest';
		$return = $this->memcacheObj->has($key);
		$this->assertFalse($return);
		$return = $this->memcacheObj->get('non exist');
		$this->assertEquals($return, null);

		/**
		 *	String Test
		 *
		 *  Pass a string data to cache
		 */
		$this->memcacheObj->put($key,"test");
		$return= $this->memcacheObj->get($key);
		$this->assertEquals($return, "test");

		/**
		 *	Array Test
		 *
		 *  Pass an array data to cache
		 */
		
		$arr = array(
				'index_one' => 'test_data_one',
				'index_two' => 'test_data_two'
				);

		$key = 'arrayTest';
		//var_dump($arr);
		$castedArr = (array) $arr;
		$this->memcacheObj->put($key,$castedArr);
		$return= $this->memcacheObj->get($key);
		
		$castedReturn = (array) $return;
		$this->assertEquals($castedReturn['index_one'], $castedArr['index_one']);
		$this->assertEquals($castedReturn['index_two'], $castedArr['index_two']);
		$this->assertEquals($castedArr, $castedReturn);

		/**
		 *	Null Test
		 *
		 *  Pass null to cache
		 */
		$key = 'nullTest';
		$this->memcacheObj->put($key,null);
		$return= $this->memcacheObj->get($key);
		$this->assertEquals($return, null);


		/**
		 *	JSON Test
		 *
		 *  Pass a JSON data to cache
		 */
		$arr = array(
				'1' => 'test1',
				'2' => 'test2'
				);
		$arr = json_encode($arr);
		$key = 'arrayTest';
		$this->memcacheObj->put($key,$arr);
		$return= $this->memcacheObj->get($key);
		$this->assertEquals($return, $arr);

		/**
		 *	Oject Test
		 *
		 *  Pass a Objectto cache
		 * !!!!!!!!!!CHECK IF IT IS PUBLIC!!!!!!!!
		 *create an empty/generic object
		 * $obj = new stdClass();
		 */
		
		$obj = new stdClass();
		var_dump($obj);
		$key = 'objectTest';
		$this->memcacheObj->put($key,$obj);
		$return= $this->memcacheObj->get($key);
		var_dump($return);
		$this->assertEquals($obj, $return);
		
	}


	function testForget()
	{
		/**
		 *	Precondition
		 *
		 *  Check if there is nothing
		 */
		$key = 'Test_Key';
		$data = 'Test_Data';
		$return = $this->memcacheObj->has($key);
		$this->assertFalse($return);

		//Add a data
		$this->memcacheObj->put($key, $data);

		//Check if the data exists
		$return = $this->memcacheObj->has($key);
		$this->assertTrue($return);

		/**
		 *  Remove the data
		 */
		$this->memcacheObj->forget($key);
		
		//Check if the data being removed
		$return = $this->memcacheObj->has($key);
	}	
	
	/**
	 *
	 *	@depends testPutAndGet
	 *	@depends testHas
	 *	@depends testForget
	 */
	function testForgetAll()
	{
		/**
		 *	Insert two data
		 */
		//First Data
		$key = 'Test_Key';
		$data = 'Test_Data';
		$this->memcacheObj->put($key,$data);
		$return=$this->memcacheObj->get($key);
		$this->assertEquals($return, $data);

		/**
		 *	Validate the data
		 */
		$return = $this->memcacheObj->has($key);
		$this->assertTrue($return);

		//Second Data
		$key2 = 'Test_Key2';
		$data2 = 'Test_Data2';
		$this->memcacheObj->put($key2,$data2);
		$return=$this->memcacheObj->get($key2);
		$this->assertEquals($return, $data2);

		/**
		 *	Validate the data
		 */
		$return = $this->memcacheObj->has($key);
		$this->assertTrue($return);

		/**
		 *	Remove all data
		 */
		$this->memcacheObj->forgetAll();

		//Check if all data are removed
		$return = $this->memcacheObj->has($key);
		$this->assertFalse($return);
		$return = $this->memcacheObj->has($key2);
		$this->assertFalse($return);
	}

  }
?>