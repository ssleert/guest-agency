<?php namespace GuestAgency\Validators;

use Respect\Validation\Validator as v;

class PageValidator implements Validator {
  use AssertTrait;

  private mixed $rules;

  public function __construct() {
    $this->rules = v::key("offset", v::greaterThan(-1))
                    ->key("limit", v::intVal()->between(1, 100));
  }
}
