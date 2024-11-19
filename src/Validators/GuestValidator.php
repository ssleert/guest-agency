<?php namespace GuestAgency\Validators;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class GuestValidator implements Validator {
  use AssertTrait;

  private mixed $rules;

  public function __construct() {
    $this->rules = v::key("name", v::stringType())
                    ->key("surname", v::stringType())
                    ->key("email", v::email())
                    ->key("phone", v::phone())
                    ->key("country", v::countryCode("alpha-2"), false);
  }
}
