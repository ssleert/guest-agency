<?php namespace GuestAgency\Middlewares;

use Amp\Http\Server\Middleware;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;

class XRequestTime implements Middleware {
  public function handleRequest(Request $request, RequestHandler $next): Response {
    $requestTime = microtime(true);

    $response = $next->handleRequest($request);
    $response->setHeader("X-Debug-Time", microtime(true) - $requestTime);

    return $response;
  }
}
