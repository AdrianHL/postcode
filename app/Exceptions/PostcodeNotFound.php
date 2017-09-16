<?php

namespace App\Exceptions;

class PostcodeNotFound extends \Exception
{

    public function render($request)
    {
        return "Postcode Not found";
    }
}