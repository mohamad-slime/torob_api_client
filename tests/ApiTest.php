<?php
// tests/ApiTest.php
// PHPUnit test for Slim API endpoints

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

require_once __DIR__ . '/../v1/torob.php';
require_once __DIR__ . '/../v1/index.php';

class ApiTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $this->app = AppFactory::create();
        $this->app->addRoutingMiddleware();
        $this->app->addErrorMiddleware(true, true, true);
    }

    public function testSearchEndpointWithQuery()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/search?q=iphone');
        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSearchEndpointWithNoQuery()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/search');
        $response = $this->app->handle($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testSpecialOffersEndpointWithNoPage()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/specialoffers');
        $response = $this->app->handle($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testSellersEndpointWithNoPrk()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/sellers');
        $response = $this->app->handle($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testDetailsEndpointWithNoPrk()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/details');
        $response = $this->app->handle($request);
        $this->assertEquals(400, $response->getStatusCode());
    }
}

