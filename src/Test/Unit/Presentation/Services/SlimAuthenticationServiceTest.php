<?php
namespace Test\Unit\Presentation\Services;
use Test\TestBase;
use Presentation\Services\SlimAuthenticationService;

class SlimAuthenticationServiceTest extends TestBase
{
    protected $slim;
    protected $request;

    protected $userRepo;
    protected $user;

    protected $service;

    public function setUp()
    {
        parent::setUp();
        $this->slim = $this->getMockBuilder('Slim')
                           ->disableOriginalConstructor()
                           ->getMock();
        //stub slim object
        $this->request = $this->getMockBuilder('Slim_Http_Request')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->request->expects($this->any())
                      ->method('getPath')
                      ->will($this->returnValue('/admin'));
        
        $this->slim->expects($this->any())
                   ->method('request')
                   ->will($this->returnValue($this->request));

        $this->userRepo = $this->getMock('Domain\\Repositories\\UserRepository');
        $this->user = $this->loadFixture('Test\\Fixtures\\User\\UserNoPosts', 'Domain\\Entities\\User');

        $this->service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->service->addRoute('admin');
    }

    public function test_constructor()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->assertInstanceOf('Slim', $this->getObjectValue($service, 'slim'));
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->getObjectValue($service, 'userRepo'));
        $this->assertEmpty($this->getObjectValue($service, 'routes'));
    }

    public function test_addRoute_should_add_route_to_internal_collection()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $service->addRoute("admin");

        $this->assertEquals(['admin'], $this->getObjectValue($service, 'routes'));
    }

    public function test_addRoute_should_return_self()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->assertSame($service, $service->addRoute('admin'));
    }

    public function test_isAuthenticated_should_return_true_when_no_routes()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->assertTrue($service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_false_for_null_cookie_on_current_path()
    {
        $this->cookieReturnsNull();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_false_when_token_invalid()
    {
        $this->cookieReturnsInvalidToken();
        $this->userRepoReturnsUserFixture();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));               
    }

    public function test_isAuthenticated_returns_false_when_no_users_found()
    {
        $this->cookieReturnsValidCookie();
        $this->userRepoReturnsEmptyArray();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_false_when_now_greater_than_user_timeout()
    {
        $this->cookieReturnsValidCookie();
        $this->user->setTimeout(strtotime("-1 week"));
        $this->userRepoReturnsUserFixture();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_true_when_valid_token_and_timeout()
    {
        $this->cookieReturnsValidCookie();
        $this->user->setTimeout(strtotime("+1 week"));
        $this->userRepoReturnsUserFixture();
        $this->assertTrue($this->service->isAuthenticated('cookiename'));
    }

    public function test_matchesCurrentRoute_should_match_pattern()
    {
        $match = $this->service->matchesCurrentRoute('/^\/ad.*/');
        $this->assertTrue($match);
    }

    protected function cookieReturnsInvalidIdentifier()
    {
        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue('notfound' . ':' . $this->user->getToken()));
    }

    protected function userRepoReturnsEmptyArray()
    {
        $this->userRepo->expects($this->once())
                       ->method('getBy')
                       ->will($this->returnValue([]));
    }

    protected function userRepoReturnsUserFixture()
    {
        $this->userRepo->expects($this->once())
                       ->method('getBy')
                       ->with($this->identicalTo(['identifier' => $this->user->getIdentifier()]))
                       ->will($this->returnValue([$this->user]));
    }

    protected function cookieReturnsInvalidToken($value='')
    {
        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue($this->user->getIdentifier() . ':' . 'invalidtoken'));
    }

    protected function cookieReturnsNull()
    {
        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue(null));
    }

    protected function cookieReturnsValidCookie()
    {
        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue($this->user->getTokenString()));
    }
}