<?php

declare(strict_types=1);

namespace TheRyanHowell\PrometheusBundle;

use TheRyanHowell\PrometheusBundle\Exceptions\PrometheusError;
use TheRyanHowell\PrometheusBundle\Exceptions\AuthenticationError;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Prometheus
{
    private $url;
    private $authenticated;
    private $username;
    private $password;
    private $guzzle;
    private $hostPath;

    public function __construct(
        string $url,
        bool $authenticated = false,
        ?string $username = null,
        ?string $password = null
    ) {
        $this->url = $url;
        $this->authenticated = $authenticated;
        $this->username = $username;
        $this->password = $password;

        $guzzleOptions = [
            // Base URI is used with relative requests
            'base_uri' => $this->url,
            // You can set any number of default request options.
            'timeout' => 2.0,
        ];

        if (true === $this->authenticated) {
            $guzzleOptions['auth'] = [$this->username, $this->password];
        }

        $this->guzzle = new Client($guzzleOptions);
    }

    public function queryInstant(
        string $query,
        ?\DateTimeInterface $time = null,
        ?int $timeout = null
    ): array {
        $params = [
            'query' => $query,
        ];

        if (null !== $time) {
            $params['time'] = $time->getTimestamp();
        }

        if (null !== $timeout) {
            $params['timeout'] = $timeout;
        }

        $response = $this->request(
            'POST',
            '/api/v1/query',
            [
                'form_params' => $params,
            ]
        );

        return $response;
    }

    public function queryRange(
        string $query,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        string $step,
        ?int $timeout = null
    ): array {
        $params = [
            'query' => $query,
            'start' => $start->getTimestamp(),
            'end' => $end->getTimestamp(),
            'step' => $step,
        ];

        if (null !== $timeout) {
            $params['timeout'] = $timeout;
        }

        $response = $this->request(
            'POST',
            '/api/v1/query_range',
            [
                'form_params' => $params,
            ]
        );

        return $response;
    }

    public function series(
        array $match,
        \DateTimeInterface $start,
        \DateTimeInterface $end
    ): array {
        $params = [
            'match[]' => $match,
            'start' => $start->getTimestamp(),
            'end' => $end->getTimestamp(),
        ];

        $query = \GuzzleHttp\Psr7\build_query($params, PHP_QUERY_RFC1738);

        $response = $this->request(
            'POST',
            '/api/v1/series',
            [
                'body' => $query,
            ]
        );

        return $response;
    }

    public function labels(): array
    {
        $response = $this->request('GET', '/api/v1/labels');

        return $response;
    }

    public function labelValues(string $label): array
    {
        $response = $this->request('GET', '/api/v1/label/'.$label.'/values');

        return $response;
    }

    public function targets(): array
    {
        $response = $this->request('GET', '/api/v1/targets');

        return $response;
    }

    public function rules(): array
    {
        $response = $this->request('GET', '/api/v1/rules');

        return $response;
    }

    public function alerts(): array
    {
        $response = $this->request('GET', '/api/v1/alerts');

        return $response;
    }

    public function alertManagers(): array
    {
        $response = $this->request('GET', '/api/v1/alertmanagers');

        return $response;
    }

    private function request($method, $path, $options = [])
    {
        try {
            $options['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];

            $response = $this->guzzle->request(
                $method,
                $path,
                $options
            );
        } catch (ClientException $e) {
            if (401 === $e->getCode()) {
                throw new AuthenticationError();
            } else {
                throw new PrometheusError($e->getMessage(), $e->getCode());
            }
        } catch (\Exception $e) {
            throw new PrometheusError($e->getMessage(), $e->getCode());
        }

        return json_decode((string) $response->getBody(), true);
    }
}
