<?php

namespace Tests\App\Lib\Routing;

use App\Lib\Routing\RouteLoader;
use App\Lib\Routing\UrlMatcher;
use PHPUnit\Framework\TestCase;

class UrlMatcherTest extends TestCase
{
    private $matcher;

    public function setUp()
    {
        $loader        = new RouteLoader('tests/fixtures/routing.yml');
        $collection    = $loader->loadRoutes();
        $this->matcher = new UrlMatcher($collection);
    }

    public function testMatchReturnsCorrectRoute()
    {
        $returnBlog = $this->matcher->match('/blog');
        $returnSlug = $this->matcher->match('/blog/this-is-a-post');

        $this->assertEquals(
            [
                'controller' => 'Blog',
                'action' => 'index',
                'params' => []
            ],
            $returnBlog
        );
        $this->assertEquals(
            [
                'controller' => 'Blog',
                'action' => 'getPost',
                'params' => [
                    'slug' => 'this-is-a-post'
                ]
            ],
            $returnSlug
        );
    }

    public function testMatchReturnsDefaultNotFound()
    {
        $return= $this->matcher->match('/blogo');

        $this->assertEquals(
            [
                'controller' => 'Default',
                'action' => 'notFound',
                'params' => []
            ],
            $return
        );
    }

    public function testMatchCollectionReturnsWhenFound()
    {
        $returnBlog = $this->matcher->matchCollection('/blog');
        $returnSlug = $this->matcher->matchCollection('/blog/this-is-a-post');

        $this->assertEquals(
            [
                'controller' => 'Blog',
                'action' => 'index',
                'params' => []
            ],
            $returnBlog
        );
        $this->assertEquals(
            [
                'controller' => 'Blog',
                'action' => 'getPost',
                'params' => [
                    'slug' => 'this-is-a-post'
                ]
            ],
            $returnSlug
        );
    }

    public function testMatchCollectionDoesNotReturnIfNoMatch()
    {
        $return = $this->matcher->matchCollection('/blogo');

        $this->assertEquals(null, $return);
    }
}
