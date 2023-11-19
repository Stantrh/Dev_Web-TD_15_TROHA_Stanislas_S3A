<?php

namespace iutnc\deefy\action;

class SignOutAction extends Action
{
    public function execute(): string
    {
        session_destroy();
        header('Location: ?action=default');
        exit;
    }
}