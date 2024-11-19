<?php namespace GuestAgency\Models;

class Guest {
  public function __construct(
    public string $uuid,
    public string $name,
    public string $surname,
    public string $email,
    public string $phone,
    public string $country,
  ) {}
}
