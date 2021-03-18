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
    private account $account;

    public function __construct(account $account)
    {
        $this->editServices = [
            new foodEdit(),
            new danceEdit(),
            new jazzEdit()
        ];

        $this->account = $account;
    }

    private function loopServices(string $type, $whenType){
        foreach ($this->editServices as $service){
            if ($service::editType == $type){
                return $whenType($service);
            }
        }
        throw new appException("Invalid type");
    }

    public function getContent(int $id){
        foreach ($this->editServices as $service){
            try {
                return $service->getHtmlEditContent($id, $this->account);
            }
            catch (appException $e) {
                // Do nothing
            }
        }

        return [];
    }

    public function getEmptyContent(string $type){
        return $this->loopServices($type, function (editBase $service) {
            return $service->getHtmlEditContentEmpty($this->account);
        });
    }

    public function editContent(array $post){
        if (!isset($post["type"]))
            throw new appException("invalid POST");

        return $this->loopServices($post["type"], function (editBase $service) use ($post){
            if ($post["activityId"] === "new")
                $service->processNewResponse($post, $this->account);
            else
                $service->processEditResponse($post, $this->account);
            return true;
        });
    }

    public function deleteContent(array $ids, string $type){
        return $this->loopServices($type, function (editBase $service) use ($ids) {
            return $service->processDeleteResponse($ids, $this->account);
        });
    }
}