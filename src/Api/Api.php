<?php namespace GuestAgency\Api;

use Monolog\Logger;
use Ramsey\Uuid\Uuid;
use Amp\Http\HttpStatus;
use Amp\Http\Server\Router;
use Amp\Http\Server\Request;
use function Amp\Http\Server\Middleware\stackMiddleware;
use Amp\Http\Server\RequestHandler\ClosureRequestHandler;
use Amp\Http\Server\Response;

use GuestAgency\Utils\Utils;
use GuestAgency\Repository\Repository;
use GuestAgency\Models\Guest;
use GuestAgency\Middlewares\XRequestTime;
use GuestAgency\Middlewares\Validate;
use GuestAgency\Validators\GuestValidator;
use GuestAgency\Validators\GuestOptionalFieldsValidator;
use GuestAgency\Validators\PageValidator;
use Respect\Validation\Validator as v;

class Api {
  private Repository $repository;

  public function __construct(
    private Logger $logger,
    private string $swag,
  ) {
    $this->repository = new Repository();
  }

  public function setRoutes(Router $router): void { 
    $router->addMiddleware(new XRequestTime());

    $router->addRoute("POST", "/api/v1/guest", stackMiddleware(
      new ClosureRequestHandler(
        function (Request $request) {
          $body = $request->getAttribute(Validate::class);

          $this->logger->debug("post guest body", $body);

          $uuid = Uuid::uuid4()->toString();

          $guest = new Guest(
            uuid:    $uuid,
            name:    $body["name"],
            surname: $body["surname"],
            email:   $body["email"],
            phone:   $body["phone"],
            country: $body["country"] ?? Utils::inferCountryFromPhone($body["phone"]),
          );

          $guestStr = var_export($guest, true);
          $this->logger->debug("guest object $guestStr");

          $this->repository->addGuest($guest);

          return new Response(
            status: HttpStatus::OK,
            headers: ["Content-Type" => "text/plain"],
            body: $uuid,
          );
        },
      ), 
      new Validate(
        validator: new GuestValidator(),
        queryParams: false,
      ),
    ));

    $router->addRoute("PUT", "/api/v1/guest/{uuid}", stackMiddleware(
      new ClosureRequestHandler(
        function (Request $request) {
          $uuid = $request->getAttribute(Router::class)["uuid"];
          $body = $request->getAttribute(Validate::class);

          $this->logger->debug("put guest body", $body);

          $guest = new Guest(
            uuid:    $uuid,
            name:    $body["name"],
            surname: $body["surname"],
            email:   $body["email"],
            phone:   $body["phone"],
            country: $body["country"] ?? Utils::inferCountryFromPhone($body["phone"]),
          );

          $guestStr = var_export($guest, true);
          $this->logger->debug("guest object $guestStr");

          $this->repository->updateGuest($guest);

          return new Response(status: HttpStatus::OK);
        },
      ), 
      new Validate(
        validator: new GuestValidator(),
        queryParams: false,
      ),
    ));

    $router->addRoute("GET", "/api/v1/guest", stackMiddleware(
      new ClosureRequestHandler(
        function (Request $request) { 
          $query = $request->getAttribute(Validate::class);

          $guests = $this->repository->listGuests(
            $query["offset"],
            $query["limit"],
          );
          
          return new Response(
            status: HttpStatus::OK,
            headers: ["Content-Type" => "encoding/json"],
            body: json_encode([
              "guests" => $guests
            ]),
          );
        },
      ), 
      new Validate(
        validator: new PageValidator(),
        queryParams: true,
      ),
    ));

    $router->addRoute("GET", "/api/v1/guest/{uuid}", new ClosureRequestHandler(
      function (Request $request) { 
        $uuid = $request->getAttribute(Router::class)["uuid"];

        $guest = $this->repository->getGuest($uuid);
        if ($guest == null) {
          return new Response(status: HttpStatus::NOT_FOUND);
        }

        return new Response(
          status: HttpStatus::OK,
          headers: ["Content-Type" => "encoding/json"],
          body: json_encode($guest),
        );
      },
    ));

    $router->addRoute("PATCH", "/api/v1/guest/{uuid}", stackMiddleware(
      new ClosureRequestHandler(
        function (Request $request) { 
          $uuid = $request->getAttribute(Router::class)["uuid"];
          $body = $request->getAttribute(Validate::class);

          $this->logger->debug("patch guest body", $body);

          $guest = $this->repository->getGuest($uuid);
          if ($guest == null) {
            return new Response(status: HttpStatus::NOT_FOUND);
          }

          foreach ($body as $key => $value) {
            if ($guest->$key == null) {
              continue;
            }
            $guest->$key = $value;
          }

          $this->repository->updateGuest($guest);

          return new Response(status: HttpStatus::OK);
        },
      ), 
      new Validate(
        validator: new GuestOptionalFieldsValidator(),
        queryParams: false,
      ),
    ));
  }
}
