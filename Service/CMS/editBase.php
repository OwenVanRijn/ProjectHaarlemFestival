<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once($root . "/Model/account.php");
require_once ($root . "/Service/baseService.php");
require_once ($root . "/Utils/appException.php");
require_once ($root . "/DAL/locationDAO.php");
require_once ("editInterface.php");
require_once ($root . "/Service/activityLogService.php");

abstract class editBase
{
    protected account $account;

    public function __construct(account $account){
        $this->account = $account;
    }

    protected function stripHtmlChars($input){
        switch (gettype($input)){
            case "string":
                if (empty($input))
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
                else
                    $correctedPostResponse[$hk . "Incomplete"] = true;
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

    public abstract function getHtmlEditContent(int $id);
}