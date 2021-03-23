<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/danceArtistService.php");

class danceArtistEdit extends editBase implements editUpdate
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

    public function processEditResponse(array $post){
        $post = $this->filterHtmlEditResponse($post);

        if (!array_key_exists("id", $post))
            throw new appException("Invalid POST");

        $artist = new danceArtist();
        $artist->setId((int)$post["id"]);

        if (array_key_exists("name", $post)){
            $artist->setName($post["name"]);

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $target_dir = $root . "/img/Artists";
            $target_file = $target_dir . "/" . strtolower(str_replace(" ", "", $post["name"])) . ".png";

            $this->handleImage($target_file);
        }

        if (array_key_exists("description", $post))
            $artist->setDescription($post["description"]);

        $this->das->updateArtist($artist);
    }
}