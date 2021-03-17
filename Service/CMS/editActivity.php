<?php

require_once ("foodEdit.php");
require_once ("danceEdit.php");
require_once ("jazzEdit.php");

class editActivity
{
    /**
     * @var editBase[]
     */
    private array $editServices;

    public function __construct()
    {
        $this->editServices = [
            new foodEdit(),
            new danceEdit(),
            new jazzEdit()
        ];
    }

    public function getContent(int $id, account $account){
        foreach ($this->editServices as $service){
            try {
                return $service->getHtmlEditContent($id, $account);
            }
            catch (appException $e) {
                // Do nothing
            }
        }

        return [];
    }

    public function getEmptyContent(account $account, string $type){
        switch ($type){ // TODO: change to foreach
            case "Dance":
                return $this->editServices[1]->getHtmlEditContentEmpty($account);
            case "Food":
                return $this->editServices[0]->getHtmlEditContentEmpty($account);
            case "Jazz":
                return $this->editServices[2]->getHtmlEditContentEmpty($account);
            default:
                throw new appException("Invalid type");
        }
    }

    public function editContent(array $post, account $account){
        if (!isset($post["type"]))
            throw new appException("invalid POST");

        foreach ($this->editServices as $service){
            if ($service::editType == $post["type"]){
                if ($post["activityId"] === "new")
                    $service->processNewResponse($post, $account);
                else
                    $service->processEditResponse($post, $account);
                return true;
            }
        }

        return false;
    }

    public function deleteContent(array $ids, string $type, account $account){
        foreach ($this->editServices as $service){
            if ($service::editType == $type){
                $service->processDeleteResponse($ids, $account);
                return;
            }
        }
    }
}