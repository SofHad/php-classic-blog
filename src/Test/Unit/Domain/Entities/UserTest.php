<?php
namespace Test\Unit\Entities;
use Domain\Entities\User;
use Domain\Entities\Post;
use Test\TestBase;
use Doctrine\Common\Collections\ArrayCollection;

class UserTest extends TestBase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->loadFixture('Test\\Fixtures\\User\\UserNoPosts', 'Domain\\Entities\\User');
    }

    public function test_getId_returns_id_value()
    {
        $this->assertEquals(1, $this->user->getId());
    }

    public function test_getUsername_should_return_username_value()
    {
        $this->assertEquals("johnny.test", $this->user->getUsername());
    }

    public function test_setUsername_should_set_username_value()
    {
        $this->user->setUsername("brian.scaturro");
        $this->assertEquals("brian.scaturro", $this->getObjectValue($this->user, "username"));
    }

    public function test_getPassword_should_return_password_value()
    {
        $this->assertEquals('password', $this->user->getPassword());
    }

    public function test_setPassword_should_set_password_value()
    {
        $this->user->setPassword('newpassword');
        $this->assertEquals('newpassword', $this->getObjectValue($this->user, 'password'));
    }

    public function test_getIdentifier_should_return_identifier_value()
    {
        $this->assertEquals('some_id', $this->user->getIdentifier());
    }

    public function test_setIdentifier_should_set_identifier_value()
    {
        $this->user->setIdentifier('some_other_id');
        $this->assertEquals('some_other_id', $this->getObjectValue($this->user, 'identifier'));
    }

    public function test_getToken_should_return_token_value()
    {
        $this->assertEquals('some_token', $this->user->getToken());
    }

    public function test_setToken_should_set_token_value()
    {
        $this->user->setToken('some_new_token');
        $this->assertEquals('some_new_token', $this->getObjectValue($this->user, 'token'));
    }

    public function test_getTimeout_should_return_timeout_value()
    {
        $this->assertEquals(10, $this->user->getTimeout());
    }

    public function test_setTimeout_should_set_timeout_value()
    {
        $this->user->setTimeout(15);
        $this->assertEquals(15, $this->getObjectValue($this->user, 'timeout'));
    }

    public function test_getDate_should_return_date_value()
    {
        $date = \DateTime::createFromFormat('m/d/Y', '06/20/1986');
        $this->assertEquals($date, $this->user->getDate());
    }

    public function test_setDate_should_set_date_value()
    {
        $date = new \DateTime('now');
        $this->user->setDate($date);
        $this->assertEquals($date, $this->getObjectValue($this->user, 'date'));
    }

    public function test_date_should_default_to_now()
    {
        $now = new \DateTime('now');
        $user = new User();
        $this->assertEquals($now, $user->getDate());
    }

    public function test_posts_should_be_doctrine_array_collection()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->getObjectValue($this->user, 'posts'));
    }

    public function test_addPost_should_add_to_posts_collection()
    {
        $post = new Post();
        $this->user->addPost($post);
        $posts = $this->getObjectValue($this->user, 'posts');
        $this->assertContains($post, $posts);
    }

    public function test_isNew_returns_true_if_id_not_set()
    {
        $this->setObjectValue($this->user, 'id', null);

        $this->assertTrue($this->user->isNew());
    }

    public function test_getPosts_should_return_posts_collection()
    {
        $collection = new ArrayCollection();
        $post = new Post();
        $collection[] = $post;
        $this->setObjectValue($this->user, 'posts', $collection);
        $this->assertContains($post, $this->user->getPosts());
    }

    public function test_refreshTimeout_sets_user_timeout_to_now_plus_one_week_default()
    {
        $time = new \DateTime('now');
        $time->add(new \DateInterval('P1W'));

        $this->user->refreshTimeout();

        $this->assertEquals($time->getTimestamp(), $this->user->getTimeout());
    }

    public function test_refreshTimeout_sets_user_timeout_to_now_plus_specified_interval()
    {
        $time = new \DateTime('now');
        $interval = new \DateInterval('P3W');
        $time->add($interval);

        $this->user->refreshTimeout($interval);

        $this->assertEquals($time->getTimestamp(), $this->user->getTimeout());
    }

    public function test_refreshIdentifier_sets_user_identifier_to_different_value()
    {
        $currentId = $this->user->getIdentifier();

        $this->user->refreshIdentifier();

        $this->assertNotEquals($currentId, $this->user->getIdentifier());
    }

    public function test_refreshToken_sets_user_token_to_different_value()
    {
        $currentToken = $this->user->getToken();

        $this->user->refreshToken();

        $this->assertNotEquals($currentToken, $this->user->getToken());
    }

    public function test_refresh_updates_all_values()
    {
        $currentToken = $this->user->getToken();
        $currentId = $this->user->getIdentifier();
        $currentTime = $this->user->getTimeout();
        $current = [$currentToken, $currentId, $currentTime];

        $this->user->refresh();

        $newToken = $this->user->getToken();
        $newId = $this->user->getIdentifier();
        $newTime = $this->user->getTimeout();
        $new = [$newToken, $newId, $newTime];

        $this->assertNotEquals($current, $new);
    }

    public function test_create_method_creates_new_user_with_username_and_password()
    {
        $user = new User();
        $user->setUsername('brian');
        $user->setPassword('password');

        $created = User::create('brian', 'password');

        $this->assertEquals($user, $created);
    }
}

