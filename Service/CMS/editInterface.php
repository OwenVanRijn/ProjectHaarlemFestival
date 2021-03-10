<?php


interface editInterface
{
    function getHtmlEditFields(sqlModel $a) : array;
    function getHtmlEditContent(int $id, account $account): array;

}