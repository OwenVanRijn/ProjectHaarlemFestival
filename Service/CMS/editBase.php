<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once($root . "/Model/account.php");
require_once ($root . "/Service/baseService.php");
require_once ($root . "/Utils/appException.php");
require_once ($root . "/DAL/locationDAO.php");
require_once ("editInterface.php");
require_once ($root . "/Service/activityLogService.php");
require_once ("editRequest.php");
require_once ("editUpdate.php");

abstract class editBase implements editRequest
{
    protected account $account;

    public function __construct(account $account){
        $this->account = $account;
    }

    protected function stripHtmlChars($input){
        switch (gettype($input)){
            case "string":
                if (ctype_space($input))
                    throw new appException("Empty string provided!");

                return trim(htmlspecialchars($input, ENT_QUOTES));
            case "array":
                $new = [];
                foreach ($input as $a){
                    $new[] = $this->stripHtmlChars($a);
                }
                return $new;
            default:
                throw new appException("Can't strip type " . gettype($input));
        }
    }

    protected const htmlEditHeader = [];

    protected abstract function getHtmlEditFields($entry);

    protected function getAllHtmlEditFields($entry) {
        return $this->getHtmlEditFields($entry);
    }

    protected function getHtmlEditHeader(){
        return static::htmlEditHeader;
    }

    public function filterHtmlEditResponse(array $postResonse){
        $header = $this->getHtmlEditHeader();
        $correctedPostResponse = [];

        foreach ($header as $hk => $hv){
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($this->account->getCombinedRole() & $v[1]))
                        if (array_key_exists($k, $postResonse))
                            $correctedPostResponse[$k] = $this->stripHtmlChars($postResonse[$k]);
                        else
                            $correctedPostResponse[$hk . "Incomplete"] = true;
                }
                elseif (array_key_exists($k, $postResonse))
                    $correctedPostResponse[$k] = $this->stripHtmlChars($postResonse[$k]);
                elseif ($v == htmlTypeEnum::imgUpload || $v == htmlTypeEnum::tableView){
                    continue;
                }
                else {
                    $correctedPostResponse[$hk . "Incomplete"] = true;
                }

            }
        }

        return $correctedPostResponse;
    }

    protected function packHtmlEditContent($fields){
        $header = $this->getHtmlEditHeader();
        $res = [];
        foreach ($header as $hk => $hv){
            $classField = [];
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($v[1] >= 0x10) ? (($this->account->getCombinedRole() & $v[1]) == $v[1]) : ($this->account->getRole() >= $v[1]))
                        $classField[$k] = ["type" => $v[0], "value" => $fields[$k]];
                }
                else
                    $classField[$k] = ["type" => $v, "value" => $fields[$k]];
            }
            $res[$hk] = $classField;
        }

        return $res;
    }

    // TODO: This is a hack. i'm lazy. i blame php
    // TODO: implement actual error messages for this
    protected function handleImage($target_file){
        if (!isset($_FILES) || !isset($_FILES["image"]) || empty($_FILES["image"]["tmp_name"]))
            return;

        if (!getimagesize($_FILES["image"]["tmp_name"])) // is this a valid image?
            throw new appException("File uploaded is not an image");

        if ($_FILES["image"]["size"] > 0x100000) // Is the file over 1MB?
            throw new appException("Uploaded file is too large");

        $imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION));

        if ($imageFileType != "png") // We only support png's
            throw new appException("Only png's are supported");

        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    public abstract function getHtmlEditContent(int $id);
}