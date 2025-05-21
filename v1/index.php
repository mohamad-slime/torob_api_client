<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/torob.php';

header('Content-Type: application/json');

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->setBasePath('/v1');
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

/**
 * Helper function to write JSON response
 */
function writeJson(Response $response, $data, int $status = 200): Response
{
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    return $response->withStatus($status);
}

$app->get('/api/search', function (Request $request, Response $response) {
    $torob = new Torob();
    $queryParams = $request->getQueryParams();
    $searchParm = $queryParams['q'] ?? null;
    $page = $queryParams['page'] ?? 1;
    if (empty($searchParm)) {
        return writeJson($response, ['error' => 'Invalid search query'], 400);
    }
    $results = $torob->search($searchParm, $page);
    return writeJson($response, $results);
});

$app->get('/api/specialoffers', function (Request $request, Response $response) {
    $torob = new Torob();
    $queryParams = $request->getQueryParams();
    $page = $queryParams['page'] ?? 1;
    if (empty($page)) {
        return writeJson($response, ['error' => 'Invalid page parameter'], 400);
    }
    $results = $torob->specialOffers($page);
    return writeJson($response, $results);
});

$app->get('/api/sellers', function (Request $request, Response $response) {
    $torob = new Torob();
    $queryParams = $request->getQueryParams();
    $prk = $queryParams['prk'] ?? null;
    if (empty($prk)) {
        return writeJson($response, ['error' => 'Invalid prk parameter'], 400);
    }
    $results = $torob->sellers($prk);
    return writeJson($response, $results);
});

$app->get('/api/details', function (Request $request, Response $response) {
    $torob = new Torob();
    $queryParams = $request->getQueryParams();
    $prk = $queryParams['prk'] ?? null;
    if (empty($prk)) {
        return writeJson($response, ['error' => 'Invalid prk parameter'], 400);
    }
    $results = $torob->details($prk);
    return writeJson($response, $results);
});

$app->run();
