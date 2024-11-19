<?php namespace GuestAgency\Validators;

use Respect\Validation\Exceptions\NestedValidationException;

trait AssertTrait {
  public function assert(mixed $data): ?string {
    try {
      $this->rules->assert($data);
    } catch (NestedValidationException $exception) {
      return $exception->getFullMessage();
    }

    return null;
  }
}
