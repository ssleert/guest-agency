<?php namespace GuestAgency\Validators;

use Respect\Validation\Validator as v;

class GuestOptionalFieldsValidator implements Validator {
  use AssertTrait;

  private mixed $rules;

  public function __construct() {
    $this->rules = v::key("name", v::stringType(), false)
                    ->key("surname", v::stringType(), false)
                    ->key("email", v::email(), false)
                    ->key("phone", v::phone(), false)
                    ->key("country", v::countryCode('alpha-2'), false);
  }
}
