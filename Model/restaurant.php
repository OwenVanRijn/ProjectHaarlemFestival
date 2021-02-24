<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/htmlModel.php");
require_once($root . "/Model/location.php");

class restaurant extends htmlModel
{
    private int $id;
    private location $location;
    private string $name;
    private string $description;
    private int $stars;
    private int $seats;
    private int $phoneNumber;
    private float $price;
    protected const sqlTableName = "restaurant";
    protected const sqlFields = ["id", "locationid", "name", "description", "stars", "seats", "phonenumber", "price"];
    protected const sqlLinks = ["locationid" => location::class];

    public function constructFull(int $id, location $location, string $name, string $description, int $stars, int $seats, int $phoneNumber, float $price)
    {
        $this->id = $id;
        $this->location = $location;
        $this->name = $name;
        $this->description = $description;
        $this->stars = $stars;
        $this->seats = $seats;
        $this->phoneNumber = $phoneNumber;
        $this->price = $price;
        return $this;
    }


    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "locationid" => $this->location->getId(),
            "name" => $this->name,
            "description" => $this->description,
            "stars" => $this->stars,
            "seats" => $this->seats,
            "phonenumber" => $this->phoneNumber,
            "price" => $this->price
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            location::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "name"],
            $sqlRes[self::sqlTableName . "description"],
            $sqlRes[self::sqlTableName . "stars"],
            $sqlRes[self::sqlTableName . "seats"],
            $sqlRes[self::sqlTableName . "phonenumber"],
            $sqlRes[self::sqlTableName . "price"]
        );
    }

    public static function toHtmlHeader() {
        return [
            "id" => htmlTypeEnum::hidden,
            // TODO: implement location?
            "name" => htmlTypeEnum::text,
            "description" => htmlTypeEnum::text,
            "stars" => htmlTypeEnum::number,
            "seats" => htmlTypeEnum::number,
            "phoneNumber" => htmlTypeEnum::number,
            "price" => htmlTypeEnum::number
        ];
    }

    public function toHtmlValueArray() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "stars" => $this->stars,
            "seats" => $this->seats,
            "phoneNumber" => $this->phoneNumber,
            "price" => $this->price
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getStars()
    {
        return $this->stars;
    }

    public function setStars($stars)
    {
        $this->stars = $stars;
    }

    public function getSeats()
    {
        return $this->seats;
    }

    public function setSeats($seats)
    {
        $this->seats = $seats;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

}