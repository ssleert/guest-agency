<?php namespace GuestAgency\Repository;

use Amp\Mysql\MysqlConfig;
use Amp\Mysql\MysqlConnectionPool;

use GuestAgency\Models\Guest;

class Repository {
  private MysqlConnectionPool $pool;

  public function __construct() {
    $password = getenv("MARIADB_ROOT_PASSWORD");
    $db = getenv("MARIADB_DATABASE");

    $config = MysqlConfig::fromString(
      "host=db user=root password=$password db=$db"
    );

    $this->pool = new MysqlConnectionPool($config);    

    $this->pool->execute(
      "
        CREATE TABLE IF NOT EXISTS guests (
          id      SERIAL       PRIMARY KEY,
          _uuid   VARCHAR(36)  UNIQUE NOT NULL,
          name    VARCHAR(256) NOT NULL,
          surname VARCHAR(256) NOT NULL,
          email   VARCHAR(256) UNIQUE NOT NULL,
          phone   VARCHAR(64)  UNIQUE NOT NULL,
          country VARCHAR(3)   NOT NULL
        )
      ", [],
    );
  }

  private function guestFromRow(array $row): Guest {
    return new Guest(
      uuid:    $row["_uuid"],
      name:    $row["name"],
      surname: $row["surname"],
      email:   $row["email"],
      phone:   $row["phone"],
      country: $row["country"],
    );
  }

  public function addGuest(Guest $guest): void {
    $this->pool->execute(
      "
        INSERT INTO guests (
          _uuid, name, surname,
          email, phone, country
        )
        VALUES (
          :uuid, :name, :surname, 
          :email, :phone, :country
        )
      ", (array)$guest,
    );
  }

  /**
   * @return array<Guest>
   */
  public function listGuests(int $offset, int $limit): array {
    $result = $this->pool->execute(
      "
        SELECT _uuid, name, surname,
               email, phone, country
        FROM guests
        LIMIT :limit
        OFFSET :offset
      ", [
        "offset" => $offset,
        "limit" => $limit,
      ],
    );

    $guests = [];
    foreach ($result as $row) {
      array_push($guests, $this->guestFromRow($row));
    }

    return $guests;
  }

  public function getGuest(string $uuid): ?Guest {
    $result = $this->pool->execute(
      "
        SELECT _uuid, name, surname,
               email, phone, country
        FROM guests
        WHERE _uuid = :uuid
      ", [
        "uuid" => $uuid
      ],
    );

    $row = $result->fetchRow();
    if ($row == null) {
      return null;
    }

    return $this->guestFromRow($row); 
  }

  public function updateGuest(Guest $guest): void {
    $this->pool->execute(
      "
        UPDATE guests
        SET name    = :name, 
            surname = :surname,
            email   = :email, 
            phone   = :phone, 
            country = :country
        WHERE _uuid = :uuid
      ", (array)$guest,
    );
  }
}
