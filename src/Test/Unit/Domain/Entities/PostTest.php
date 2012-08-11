<?php
namespace Test\Unit\Entities;
use Domain\Entities\User;
use Domain\Entities\Comment;
use Test\TestBase;
use Doctrine\Common\Collections\ArrayCollection;
class PostTest extends TestBase
{
    protected $post;

    public function setUp()
    {
        parent::setUp();
        $this->post = $this->loadFixture('Test\\Fixtures\\Post\\PostNoUserAndNoComments', 'Domain\\Entities\\Post');
    }

    public function test_getId_should_return_id_value()
    {
        $this->assertEquals(1, $this->post->getId());
    }

    public function test_getTitle_should_return_title_value()
    {
        $this->assertEquals("Unit Testing Is Gnarly", $this->post->getTitle());
    }

    public function test_setTitle_should_set_title_value()
    {
        $this->post->setTitle("New Title");
        $this->assertEquals("New Title", $this->getObjectValue($this->post, 'title'));
    }

    public function test_getExcerpt_should_return_excerpt_value()
    {
        $this->assertEquals("A short summary.", $this->post->getExcerpt());
    }

    public function test_setExcerpt_should_set_excerpt_value()
    {
        $this->post->setExcerpt("Another summary");
        $this->assertEquals("Another summary", $this->getObjectValue($this->post, 'excerpt'));
    }

    public function test_getContent_should_return_content_value()
    {
        $this->assertEquals("This is post content", $this->post->getContent());
    }

    public function test_setContent_should_set_content_value()
    {
        $this->post->setContent("This is new post content");
        $this->assertEquals("This is new post content", $this->getObjectValue($this->post, 'content'));
    }

    public function test_getDate_should_return_date_value()
    {
        $date = \DateTime::createFromFormat('m/d/Y', '06/20/1986');
        $this->assertEquals($date, $this->post->getDate());
    }

    public function test_setDate_should_set_date_value()
    {
        $date = new \DateTime('now');
        $this->post->setDate($date);
        $this->assertEquals($date, $this->getObjectValue($this->post, 'date'));
    }

    public function test_comments_is_Doctrine_ArrayCollection()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->getObjectValue($this->post, 'comments'));
    }

    public function test_addComment_should_add_to_comments_collection()
    {
        $comment = new Comment();
        $this->post->addComment($comment);
        $comments = $this->getObjectValue($this->post, 'comments');
        $this->assertContains($comment, $comments);
    }

    public function test_getComments_should_return_comments_collection()
    {
        $comment = new Comment();
        $collection = new ArrayCollection();
        $collection[] = $comment;
        $this->setObjectValue($this->post, 'comments', $collection);
        $this->assertContains($comment, $this->post->getComments());
    }

    public function test_getUser_should_return_user()
    {
        $user = new User();
        $this->setObjectValue($this->post, 'user', $user);
        $this->assertSame($user, $this->post->getUser());
    }

    public function test_setUser_should_set_user()
    {
        $user = new User();
        $this->post->setUser($user);
        $this->assertSame($user, $this->getObjectValue($this->post, 'user'));
    }

    public function test_setUser_should_add_post_to_user_posts()
    {
        $user = new User();
        $this->post->setUser($user);
        $this->assertContains($this->post, $user->getPosts());
    }
}