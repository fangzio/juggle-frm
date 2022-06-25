<?php

namespace Juggle\Frm\Controller;

class Controller
{
    public function init()
    {

    }

    public function run($action)
    {
        call_user_func([$this, 'action' . ucfirst($action)]);
    }
}