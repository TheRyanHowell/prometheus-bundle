<?php

declare(strict_types=1);

namespace TheRyanHowell\PrometheusBundle\Exceptions;

use TheRyanHowell\PrometheusBundle\Exceptions\PrometheusError;

class AuthenticationError extends PrometheusError
{
    protected $message = 'Unable to authenticate to prometheus server.';
    protected $code = 401;
}
