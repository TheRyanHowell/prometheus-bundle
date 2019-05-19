<?php

declare(strict_types=1);

namespace TheRyanHowell\PrometheusBundle\Exceptions;

class PrometheusError extends \Exception
{
    protected $message = 'Generic prometheus error.';
    protected $code = 500;
}
