<?php
declare(strict_types=1);

namespace TheRyanHowell\PrometheusBundle;

use TheRyanHowell\PrometheusBundle\Prometheus;
use PHPUnit\Framework\TestCase;

class PrometheusTest extends TestCase
{
    public function setUp(): void
    {
        $this->prometheus = new Prometheus('http://127.0.0.1:9090');
    }

    public function testQueryInstant()
    {
        $response = $this->prometheus->queryInstant('up');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testQueryRange()
    {
        $end = new \DateTime();
        $start = new \DateTime();
        $start = $start->modify('-5 minutes');

        $response = $this->prometheus->queryRange('up', $start, $end, '1m');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testSeries()
    {
        $end = new \DateTime();
        $start = new \DateTime();
        $start = $start->modify('-5 minutes');

        $response = $this->prometheus->series(['up', 'process_start_time_seconds'], $start, $end);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testLabels()
    {
        $response = $this->prometheus->labels();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testLabelValues()
    {
        $response = $this->prometheus->labels('up');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testTargets()
    {
        $response = $this->prometheus->targets();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testRules()
    {
        $response = $this->prometheus->rules();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testAlerts()
    {
        $response = $this->prometheus->alerts();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }

    public function testAlertManagers()
    {
        $response = $this->prometheus->alertManagers();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);
    }
}
