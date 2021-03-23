<?php
require_once ("uiGenerator.php");

class navBarCMSGenerator extends uiGenerator
{
    private string $curPageKey;

    /* Layout:

    nav
        a /a
        a /a
        a /a
    /nav

    */

    private static array $noRequirementPages = [
        "Logout" => "logout.php",
        "Home" => "home.php",
    ];

    private static array $timeVolunteerPages = [
        "View Jazz Events" => "events.php?event=jazz",
        "View Dance Events" => "events.php?event=dance",
        "View Food Events" => "events.php?event=food",
        "Dance Artists" => "danceArtists.php",
    ];

    private static array $ticketVolunteerPages = [
        "Users" => "users.php"
    ];

    private function findInArray(string $needle, array $haystack){
        if ($needle == "")
            return "";

        $var = array_search($needle, $haystack);
        if ($var === false)
            return "";

        return $var;
    }

    private function findInAllArrays(string $needle){
        $all = [self::$noRequirementPages, self::$timeVolunteerPages];

        foreach ($all as $arr){
            $res = $this->findInArray($needle, $arr);
            if ($res != "")
                return $res;
        }

        return "";
    }

    private function accessiblePages($account){
        $pages = self::$noRequirementPages;

        if (is_null($account)){
            return $pages;
        }

        if ($account->getCombinedRole() & account::accountScheduleManager){
            $pages = array_merge($pages, self::$timeVolunteerPages);
        }

        if ($account->getCombinedRole() & account::accountTicketManager){
            $pages = array_merge($pages, self::$ticketVolunteerPages);
        }

        return $pages;
    }

    public function __construct(string $currentPage = "")
    {
        if ($currentPage == "")
            $currentPage = basename($_SERVER['PHP_SELF']);

        $this->curPageKey = $this->findInAllArrays($currentPage);

        $this->cssRules = [
            "nav" => "",
            "a" => "",
            "sel" => ""
        ];
    }

    private function genATag(string $text, string $link, string $classes){
        echo '<a class="' . $classes .'" href="' . $link .'">' . $text . "</a>";
    }

    public function generate($account = null){
        echo '<nav class="' . $this->cssRules["nav"] . '">';

        foreach ($this->accessiblePages($account) as $pageName => $url){
            if ($pageName == $this->curPageKey)
                $this->genATag($pageName, "#", $this->cssRules["a"] . " " . $this->cssRules["sel"]);
            else
                $this->genATag($pageName, $url, $this->cssRules["a"]);
        }

        echo '</nav>';
    }
}