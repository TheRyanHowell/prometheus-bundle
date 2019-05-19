<?php

declare(strict_types=1);

namespace TheRyanHowell\PrometheusBundle\Exceptions;

class AuthenticationError extends PrometheusError
{
    protected $message = 'Unable to authenticate to prometheus server.';
    protected $code = 401;
}
