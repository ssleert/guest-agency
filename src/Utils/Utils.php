<?php namespace GuestAgency\Utils;

use \libphonenumber\PhoneNumberUtil;

class Utils {
  private static PhoneNumberUtil $phoneNumberInstance;

  public static function init() {
    self::$phoneNumberInstance = PhoneNumberUtil::getInstance();
  }

  public static function inferCountryFromPhone(string $phone): string {    
    $parsedNumber = self::$phoneNumberInstance->parse($phone, null);

    return self::$phoneNumberInstance->getRegionCodeForNumber($parsedNumber);
  }

  public static function jsonParse(string $buffer): array {
    return json_decode($buffer, true, 512, JSON_THROW_ON_ERROR);
  }
}

Utils::init();
