<?php


interface tableInterface
{
    /* Structure of returned data:

    [
        header => [str...],
        sections => [
            "section1" => [[str...]...],
            "section2" => [[str...]...]
        ]
    ]

    */
    function getTableContent() : array;
}