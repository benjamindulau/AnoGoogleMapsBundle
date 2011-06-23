<?php

namespace Ano\Bundle\GoogleMapsBundle\Service\Exception;

class RequestFailedException extends \RuntimeException
{
    public function __construct($message = 'Request failed', \Exception $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}
