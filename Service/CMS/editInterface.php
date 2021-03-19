<?php


interface editInterface
{
    /*
    * Template:
    * [
    *      class: [
    *          field: [
    *              type: str
    *              value: T
    * ]]]
    */
    //function getHtmlEditFields(sqlModel $a) : array;


    function getHtmlEditContent(int $id, account $account): array;
    public function processEditResponse(array $post, account $account);
}