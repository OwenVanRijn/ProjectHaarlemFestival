<?php
require_once ("sqlModel.php");
require_once ("activity.php");

class danceActivity extends sqlModel
{
    private int $id;
    private activity $activity;
    private string $type;
    private array $artists;

    protected const sqlTableName = "danceactivity";
    protected const sqlFields = ["id", "activityid", "sessionType"];
    protected const sqlLinks = ["activityid" =>activity::class];

    public function __construct(){
        $this->artists = [];
    }

    public function constructFull(int $id, activity $activity, string $type){
        $this->id = $id;
        $this->activity = $activity;
        $this->type = $type;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "activityid" => $this->activity->getId(),
            "sessionType" =>$this->activity,
        ];
    }
    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            activity::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "sessionType"]
        );
    }
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return activity
     */
    public function getActivity(): activity
    {
        return $this->activity;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getArtists(): array
    {
        return $this->artists;
    }

    public function addArtist(danceArtist $artist): void
    {
        $this->artists[] = $artist;
    }
}