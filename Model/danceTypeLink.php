<?php

require_once ("sqlModel.php");

class danceTypeLink extends sqlModel
{
    private int $id;
    private danceArtist $artist;
    private danceType $type;

    protected const sqlTableName = "dancetypelink";
    protected const sqlFields = ["id", "danceartistid", "dancetypeid"];
    protected const sqlLinks = ["danceartistid" => danceArtist::class, "dancetypeid" => danceType::class];

    public function __construct(int $id, danceArtist $artist, danceType $type){
        $this->id = $id;
        $this->artist = $artist;
        $this->type = $artist;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "danceartistid" => $this->artist->getId(),
            "dancetypeid" => $this->type->getId()
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->__construct(
            $sqlRes[self::sqlTableName . "id"],
            danceArtist::sqlParse($sqlRes),
            danceType::sqlParse($sqlRes));
    }

    public function getId(): int
    {
        return $this->id;
    }
}