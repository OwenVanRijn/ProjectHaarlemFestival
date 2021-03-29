<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/sqlModel.php");
require_once($root . "/Model/location.php");

class restaurant extends sqlModel
{
    private int $id;
    private location $location;
    private string $name;
    private string $description;
    private int $stars;
    private int $seats;
    private int $phoneNumber;
    private float $price;
    private string $parking;
    private string $website;
    private string $menu;
    private string $contact;
    protected const sqlTableName = "restaurant";
    protected const sqlFields = ["id", "locationid", "name", "description", "stars", "seats", "phonenumber", "price", "parking", "website", "menu", "contact"];
    protected const sqlLinks = ["locationid" => location::class];

    public function constructFull(int $id, location $location, string $name, string $description, int $stars, int $seats, int $phoneNumber, float $price, string $parking, string $website, string $menu, string $contact)
    {
        $this->id = $id;
        $this->location = $location;
        $this->name = $name;
        $this->description = $description;
        $this->stars = $stars;
        $this->seats = $seats;
        $this->phoneNumber = $phoneNumber;
        $this->price = $price;
        $this->parking = $parking;
        $this->website = $website;
        $this->menu = $menu;
        $this->contact = $contact;
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
            "price" => $this->price,
            "parking" => $this->parking,
            "website" => $this->website,
            "menu" => $this->menu,
            "contact" => $this->contact
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
            $sqlRes[self::sqlTableName . "price"],
            $sqlRes[self::sqlTableName . "parking"],
            $sqlRes[self::sqlTableName . "website"],
            $sqlRes[self::sqlTableName . "menu"],
            $sqlRes[self::sqlTableName . "contact"]
        );
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

    public function getParking()
    {
        return $this->parking;
    }

    public function setParking($parking)
    {
        $this->parking = $parking;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getMenu(): string
    {
        return $this->website . "/" . $this->menu;
    }

    public function getFullMenu()
    {
        return $this->website . "/" . $this->menu;
    }

    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return string
     */
    public function getContact(): string
    {
        return $this->contact;
    }

    public function getFullContact()
    {
        return $this->website . "/" . $this->contact;
    }

}