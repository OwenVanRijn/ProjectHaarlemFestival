<?php

require_once ("foodEdit.php");
require_once ("danceEdit.php");

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
            new danceEdit()
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

    public function editContent(array $post, account $account){
        if (!isset($post["type"]))
            throw new appException("invalid POST");

        foreach ($this->editServices as $service){
            if ($service::editType == $post["type"]){
                $service->processEditResponse($post, $account);
                return true;
            }
        }

        return false;
    }
}