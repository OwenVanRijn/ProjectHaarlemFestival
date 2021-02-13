<?php
    require_once("../Service/sessionService.php");
    $sessionService = new sessionService();
    $sessionService->deleteSessionFromCookie();
    header("Location: login.php");