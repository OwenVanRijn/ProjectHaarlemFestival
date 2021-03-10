<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/danceActivityService.php");
require_once ($root . "/Service/artistOnActivityService.php");

class danceEdit extends editBase
{
    private artistOnActivityService $aoaService;

    public function __construct()
    {
        parent::__construct(new danceActivityService());
        $this->aoaService = new artistOnActivityService();
    }

    public const editType = "Dance";

    public const getHtmlEditHeader = [
        "artists" => [ // TODO: This needs some custom type!
            "artistActivityId" => htmlTypeEnum::hidden,
            "eventType" => htmlTypeEnum::text,
            "artistsOnActivity" => htmlTypeEnum::listMultiple
        ]
    ];

    public function getHtmlEditFields(sqlModel $a): array
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

    protected function processEditResponseChild(array $post)
    {
        if (isset($post["artistsIncomplete"]))
            throw new appException("Artist section is incomplete!");

        $this->service->updateSessionType((int)$post["artistActivityId"], $post["eventType"]);
        $this->aoaService->updateArtistIds((int)$post["artistActivityId"], $post["artistsOnActivity"]);
    }
}