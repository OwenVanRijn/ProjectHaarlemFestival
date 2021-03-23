<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("editActivityBase.php");
require_once ($root . "/Service/danceActivityService.php");
require_once ($root . "/Service/artistOnActivityService.php");

class danceEditActivity extends editActivityBase
{
    private artistOnActivityService $aoaService;

    public function __construct(account $account)
    {
        parent::__construct(new danceActivityService(), $account);
        $this->aoaService = new artistOnActivityService();
    }

    public const editType = "Dance";

    public const htmlEditHeader = [
        "artists" => [ // TODO: This needs some custom type!
            "artistActivityId" => htmlTypeEnum::hidden,
            "eventType" => htmlTypeEnum::text,
            "artistsOnActivity" => htmlTypeEnum::listMultiple
        ]
    ];

    public function getHtmlEditFieldsChild(sqlModel $a): array
    {
        $artists = $a->getArtists();
        $artistSelStrs = [];
        foreach ($artists as $b){
            $artistSelStrs[] = $b->getId();
        }

        $artistStrs = (new danceArtistService())->getAllAsStr();

        return [
            "artistActivityId" => $a->getId(),
            "eventType" => $a->getType(),
            "artistsOnActivity" => [
                "options" => $artistStrs,
                "selected" => $artistSelStrs
            ]
        ];
    }

    public function getHtmlEditFieldsEmpty(){
        $artistStrs = (new danceArtistService())->getAllAsStr();

        return [
            "artistActivityId" => "new",
            "eventType" => "",
            "artistsOnActivity" => [
                "options" => $artistStrs,
                "selected" => []
            ]
        ];
    }

    protected function processEditResponseChild(array $post)
    {
        if (isset($post["artistsIncomplete"]))
            throw new appException("Artist section is incomplete!");

        $this->service->updateSessionType((int)$post["artistActivityId"], $post["eventType"]);
        $this->aoaService->updateArtistIds((int)$post["artistActivityId"], $post["artistsOnActivity"]);
    }

    public function processNewResponseChild(array $post, int $activityId){
        if (isset($post["artistsIncomplete"]))
            throw new appException("Artist section is incomplete!");

        $id = $this->service->insertDanceActivity($activityId, $post["eventType"]);
        $this->aoaService->updateArtistIds($id, $post["artistsOnActivity"]);
    }
}