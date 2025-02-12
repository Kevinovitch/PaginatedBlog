<?php

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

class UnauthorizedArticleDeletionException extends Exception
{
    #[Pure] public function __construct()
    {
        parent::__construct('You are not authorized to delete this article');
    }

}