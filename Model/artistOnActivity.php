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

    protected const sqlTableName = "artistsonactivity";
    protected const sqlFields = ["id", "danceartistid", "danceactivityid", "description"];
    protected const sqlLinks = ["danceartistid"=>danceArtist::class, "danceactivityid"=>danceActivity::class];

    public function __construct(){
        $this->description = "";
    }

    public function constructFull(int $id, danceArtist $artist, danceActivity $activity, $description){
        $this->id = $id;
        $this->artist = $artist;
        $this->activity = $activity;
        if (!is_null($description))
            $this->description = $description;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "danceartistid" => $this->artist->getId(),
            "dancetypeid" => $this->activity->getId(),
            "description" => $this->description
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            danceArtist::sqlParse($sqlRes),
            danceActivity::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "description"]);
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return danceArtist
     */
    public function getArtist(): danceArtist
    {
        return $this->artist;
    }

    /**
     * @return danceActivity
     */
    public function getActivity(): danceActivity
    {
        return $this->activity;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}