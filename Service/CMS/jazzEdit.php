<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/jazzactivityService.php");
require_once ($root . "/Service/jazzBandService.php");

class jazzEdit extends editBase
{
    private jazzBandService $jazzBandService;

    public function __construct()
    {
        parent::__construct(new jazzactivityService());
        $this->jazzBandService = new jazzBandService();
    }

    public const editType = "Jazz";

    public const htmlEditHeader = [
        "band" => [
            "band" => htmlTypeEnum::list,
            "bandName" => htmlTypeEnum::text,
            "bandDescription" => htmlTypeEnum::textArea,
        ],
        "performance" => [
            "jazzActivityId" => htmlTypeEnum::hidden,
            "hall" => htmlTypeEnum::text,
            "seats" => htmlTypeEnum::number
        ]
    ];

    public function getHtmlEditFields(sqlModel $a): array
    {
        $bandsStr = $this->jazzBandService->getAllAsStr();

        $selBand = $a->getJazzband();

        return [
            "band" => [
                "options" => $bandsStr,
                "selected" => $selBand->getId()
            ],
            "jazzActivityId" => $a->getId(),
            "bandName" => $selBand->getName(),
            "bandDescription" => $selBand->getDescription(),
            "hall" => $a->getHall(),
            "seats" => $a->getSeats()
        ];
    }

    public function getHtmlEditFieldsEmpty(): array
    {
        $bandsStr = $this->jazzBandService->getAllAsStr();

        return [
            "band" => [
                "options" => $bandsStr,
                "selected" => "-1"
            ],
            "jazzActivityId" => "new",
            "bandName" => "",
            "bandDescription" => "",
            "hall" => "",
            "seats" => ""
        ];
    }

    protected function processEditResponseChild(array $post)
    {
        if (isset($post["performanceIncomplete"]))
            throw new appException("Jazz form not filled in");

        $bandId = null;


        if (!isset($post["band"]))
            throw new appException("Invalid POST");

        if ((int)$post["band"] == -1){
            $res = $this->jazzBandService->insertBand($post["bandName"], $post["bandDescription"]);
            if (!$res)
                throw new appException("[JazzBand] Failed to insert...");

            $bandId = $res;
        }
        elseif (isset($post["bandIncomplete"])) {
            $bandId = (int)$post["band"];
        }
        else {
            $this->jazzBandService->updateBand((int)$post["band"], $post["bandName"], $post["bandDescription"]);
        }

        if (!$this->service->updateActivity(
            (int)$post["jazzActivityId"],
            $post["hall"],
            (int)$post["seats"],
            $bandId
        ))
            throw new appException("[Jazz] db update failed...");
    }

    // TODO: implement processNewResponseChild
}