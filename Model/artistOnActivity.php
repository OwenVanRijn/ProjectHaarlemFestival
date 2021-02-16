<?php

require_once ("sqlModel.php");
require_once ("danceArtist.php");
require_once ("danceActivity.php");

class artistOnActivity extends sqlModel
{
    private int $id;
    private danceArtist $artist;
    private danceActivity $activity;
    private string $description;

    protected const sqlTableName = "aetistonactivity";
    protected const sqlFields = ["id", "danceartistid", "danceactivityid", "description"];
    protected const sqlLinks = ["danceartistid"=>danceArtist::class, "danceactivity"=>danceActivity::class];

    public function __construct(int $id, danceArtist $artist, danceActivity $activity, string $description){
        $this->id = $id;
        $this->artist = $artist;
        $this->activity = $activity;
        $this->description = $description;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "danceartistid" => $this->artist->getId(),
            "dancetypeid" => $this->type->getId(),
            "description" => $this->description
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->__construct(
            $sqlRes[self::sqlTableName . "id"],
            danceArtist::sqlParse($sqlRes),
            danceType::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "description"]);
    }

    public function getId() : int
    {
        return $this->id;
    }
}