<?php

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

class UnauthorizedArticleEditionException extends Exception
{
    #[Pure] public function __construct()
    {
        parent::__construct('You are not authorized to edit this article');
    }
}
