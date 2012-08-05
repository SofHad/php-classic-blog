<?php
namespace Test\Unit\Entities;
use Domain\Entities\User;
use Domain\Entities\Post;
use Test\Unit\UnitTestBase;
use Doctrine\Common\Collections\ArrayCollection;

class UserTest extends UnitTestBase
{
	protected $user;

	public function setUp()
	{
		parent::setUp();
		$this->user = new User();
	}

	public function test_getId_returns_id_value()
	{
		$this->setObjectValue($this->user, 'id', 1);
		$this->assertEquals(1, $this->user->getId());
	}

	public function test_getUsername_should_return_username_value()
	{
		$this->setObjectValue($this->user, 'username', 'johnny.test');
		$this->assertEquals("johnny.test", $this->user->getUsername());
	}

	public function test_setUsername_should_set_username_value()
	{
		$this->user->setUsername("brian.scaturro");
		$this->assertEquals("brian.scaturro", $this->getObjectValue($this->user, "username"));
	}

	public function test_getPassword_should_return_password_value()
	{
		$this->setObjectValue($this->user, 'password', 'password');
		$this->assertEquals('password', $this->user->getPassword());
	}

	public function test_setPassword_should_set_password_value()
	{
		$this->user->setPassword('newpassword');
		$this->assertEquals('newpassword', $this->getObjectValue($this->user, 'password'));
	}

	public function test_getIdentifier_should_return_identifier_value()
	{
		$this->setObjectValue($this->user, 'identifier', 'some_id');
		$this->assertEquals('some_id', $this->user->getIdentifier());
	}

	public function test_setIdentifier_should_set_identifier_value()
	{
		$this->user->setIdentifier('some_other_id');
		$this->assertEquals('some_other_id', $this->getObjectValue($this->user, 'identifier'));
	}

	public function test_getToken_should_return_token_value()
	{
		$this->setObjectValue($this->user, 'token', 'some_token');
		$this->assertEquals('some_token', $this->user->getToken());
	}

	public function test_setToken_should_set_token_value()
	{
		$this->user->setToken('some_new_token');
		$this->assertEquals('some_new_token', $this->getObjectValue($this->user, 'token'));
	}

	public function test_getTimeout_should_return_timeout_value()
	{
		$this->setObjectValue($this->user, 'timeout', 10);
		$this->assertEquals(10, $this->user->getTimeout());
	}

	public function test_setTimeout_should_set_timeout_value()
	{
		$this->user->setTimeout(10);
		$this->assertEquals(10, $this->getObjectValue($this->user, 'timeout'));
	}

	public function test_getDate_should_return_date_value()
	{
		$date = new \DateTime('now');
		$this->setObjectValue($this->user, 'date', $date);
		$this->assertEquals($date, $this->user->getDate());
	}

	public function test_setDate_should_set_date_value()
	{
		$date = new \DateTime('now');
		$this->user->setDate($date);
		$this->assertEquals($date, $this->getObjectValue($this->user, 'date'));
	}

	public function test_posts_should_be_doctrine_array_collection()
	{
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->getObjectValue($this->user, 'posts'));
	}

	public function test_addPost_should_add_to_posts_collection()
	{
		$this->user->addPost(new Post());
		$posts = $this->getObjectValue($this->user, 'posts');
		$this->assertEquals(1, count($posts));
	}

	public function test_getPosts_should_return_posts_collection()
	{
		$collection = new ArrayCollection();
		$collection[] = new Post();
		$this->setObjectValue($this->user, 'posts', $collection);
		$this->assertEquals(1, count($this->user->getPosts()));
	}
}
