<?php namespace GuestAgency\Middlewares;

use Amp\Http\HttpStatus;
use Amp\Http\Server\Middleware;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

use GuestAgency\Validators\Validator;
use GuestAgency\Utils\Utils;

class Validate implements Middleware { 
  public function __construct(
    private Validator $validator,
    private bool $queryParams,
  ) {}

  public function handleRequest(Request $request, RequestHandler $next): Response {
    $data = [];

    if ($this->queryParams) {
      parse_str($request->getUri()->getQuery(), $data);
    } else {
      $data = Utils::jsonParse($request->getBody()->buffer());
    }

    $validationMessage = $this->validator->assert($data);
    if ($validationMessage != null) {
      return new Response(
        status: HttpStatus::FORBIDDEN,
        headers: ["Content-Type" => "text/plain"],
        body: $exception->getFullMessage(),
      );
    }

    $request->setAttribute($this::class, $data);

    $response = $next->handleRequest($request);
    return $response;
  }
}
