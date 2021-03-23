<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/danceArtistService.php");

class danceArtistEdit extends editBase
{
    private danceArtistService $das;

    public function __construct(account $account)
    {
        parent::__construct($account);
        $this->das = new danceArtistService();
    }

    protected const htmlEditHeader = [
        "artist" => [
            "id" => htmlTypeEnum::hidden,
            "name" => htmlTypeEnum::text,
            "description" => htmlTypeEnum::textArea,
            "image" => htmlTypeEnum::imgUpload
        ]
    ];

    protected function getHtmlEditFields($entry)
    {
        return [
            "id" => $entry->getId(),
            "name" => $entry->getName(),
            "description" => $entry->getDescription(),
            "image" => ""
        ];
    }

    public function getHtmlEditContent(int $id)
    {
        $artist = $this->das->getFromId($id);

        if (is_null($artist))
            throw new appException("Invalid artist");

        return $this->packHtmlEditContent($this->getHtmlEditFields($artist));
    }
}