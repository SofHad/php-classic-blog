<?php
namespace Test\Unit\Entities;
use Domain\Entities\User;
use Test\Unit\UnitTestBase;
class UserTest extends UnitTestBase
{
	protected $user;

	public function setUp()
	{
		parent::setUp();
		$this->user = new User();
	}

	public function test_Get_Username_should_return_username_value()
	{
		$this->setObjectValue($this->user, 'username', 'johnny.test');
		$this->assertEquals("johnny.test", $this->user->getUsername());
	}

	public function test_Set_username_should_set_username_value()
	{
		$this->user->setUsername("brian.scaturro");
		$this->assertEquals("brian.scaturro", $this->getObjectValue($this->user, "username"));
	}

	public function test_Get_password_should_return_password_value()
	{
		$this->setObjectValue($this->user, 'password', 'password');
		$this->assertEquals('password', $this->user->getPassword());
	}

	public function test_Set_password_should_set_password_value()
	{
		$this->user->setPassword('newpassword');
		$this->assertEquals('newpassword', $this->getObjectValue($this->user, 'password'));
	}

	public function test_Get_identifier_should_return_identifier_value()
	{
		$this->setObjectValue($this->user, 'identifier', 'some_id');
		$this->assertEquals('some_id', $this->user->getIdentifier());
	}

	public function test_Set_identifier_should_set_identifier_value()
	{
		$this->user->setIdentifier('some_other_id');
		$this->assertEquals('some_other_id', $this->getObjectValue($this->user, 'identifier'));
	}

	public function test_Get_token_should_return_token_value()
	{
		$this->setObjectValue($this->user, 'token', 'some_token');
		$this->assertEquals('some_token', $this->user->getToken());
	}

	public function test_Set_token_should_set_token_value()
	{
		$this->user->setToken('some_new_token');
		$this->assertEquals('some_new_token', $this->getObjectValue($this->user, 'token'));
	}

	public function test_Get_timeout_should_return_timeout_value()
	{
		$this->setObjectValue($this->user, 'timeout', 10);
		$this->assertEquals(10, $this->user->getTimeout());
	}

	public function test_Set_timeout_should_set_timeout_value()
	{
		$this->user->setTimeout(10);
		$this->assertEquals(10, $this->getObjectValue($this->user, 'timeout'));
	}

	public function test_Get_date_should_return_date_value()
	{
		$date = new \DateTime('now');
		$this->setObjectValue($this->user, 'date', $date);
		$this->assertEquals($date, $this->user->getDate());
	}

	public function test_Set_date_should_set_date_value()
	{
		$date = new \DateTime('now');
		$this->user->setDate($date);
		$this->assertEquals($date, $this->getObjectValue($this->user, 'date'));
	}
}

