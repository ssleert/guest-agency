<?php declare(strict_types = 1); 
 
require __DIR__ . "/../vendor/autoload.php";

use Amp\ByteStream;
use Amp\Http\HttpStatus;
use Amp\Http\Server\DefaultErrorHandler;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Middleware\ForwardedHeaderType;
use Amp\Http\Server\SocketHttpServer;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Amp\Http\Server\RequestHandler\ClosureRequestHandler;
use Amp\Http\Server\Router;

use GuestAgency\Api\Api;

$logHandler = new StreamHandler(ByteStream\getStdout());
$logHandler->pushProcessor(new PsrLogMessageProcessor());
$logHandler->setFormatter(new ConsoleFormatter());

$logger = new Logger("GuestAgency");
$logger->pushHandler($logHandler);

$server = SocketHttpServer::createForBehindProxy(
  logger: $logger, 
  headerType: ForwardedHeaderType::XForwardedFor, 
  trustedProxies: [],
  concurrencyLimit: null,
);
$errorHandler = new DefaultErrorHandler();

$router = new Router($server, $logger, $errorHandler);

$api = new Api($logger, "swag");
$api->setRoutes($router);

$server->expose("0.0.0.0:80");

$server->start($router, $errorHandler);

Amp\trapSignal([SIGINT, SIGTERM]);

$server->stop();
