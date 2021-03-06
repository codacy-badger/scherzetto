<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Psr7\Response;
use Lib\Application;
use Lib\Http\Request;
use Lib\Routing\Router;
use PHPUnit\Framework\MockObject\MockObject;

class ApplicationTest extends TestCase
{
    protected $app;

    /**
     * @var Router&MockObject
     */
    protected $router;

    public function setUp()
    {
        $this->router = $this
            ->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['route'])
            ->getMock();

        $this->router
             ->expects($this->once())
             ->method('route')
             ->willReturn(['controller' => 'DefaultController', 'action' => 'notFoundAction', 'params' => []]);

        $this->app = new Application($this->router);
    }

    public function testRouterIsCalled()
    {
        $this->app->handleRequest(Request::createFromGlobals());
    }

    public function testHandleRequestReturnsResponse()
    {
        $return = $this->app->handleRequest(new Request());
        $this->assertInstanceOf(Response::class, $return);
    }
}
