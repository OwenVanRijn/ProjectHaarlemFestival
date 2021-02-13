<?php
    require_once("../Service/sessionService.php");
    $sessionService = new sessionService();

    $user = $sessionService->validateSessionFromCookie();

    if ($user)
        header("Location: home.php");