<?php

/**
 * Class SystemCheckTest
 */
class SystemCheckTest extends AbanteCartTest{
	private $original_save_path='';
	protected function setUp(){
		parent::__construct();

		$this->original_save_path = ini_get('session.save_path');
	}

	protected function tearDown(){
		ini_set('session.save_path', $this->original_save_path);
	}

	public function testGetUniqueSeoKeyword(){
		ini_set('session.save_path', '');
		$result = check_session_save_path();
		$this->assertEquals($result, array());

		ini_set('session.save_path', '/tmp');
		$result = check_session_save_path();
		$this->assertEquals($result, array());

		ini_set('session.save_path', '1;/tmp');
		$result = check_session_save_path();
		$this->assertEquals($result, array());


		ini_set('session.save_path', '1;0666;/tmp');
		$result = check_session_save_path();
		$this->assertEquals($result, array());

	}
}