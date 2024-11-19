<?php namespace GuestAgency\Validators;

interface Validator {
  public function assert(mixed $data): ?string;
}
