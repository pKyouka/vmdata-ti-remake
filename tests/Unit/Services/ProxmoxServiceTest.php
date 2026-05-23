<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\ProxmoxService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxmoxServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock config values
        config([
            'services.proxmox.host' => 'https://pve.example.com',
            'services.proxmox.username' => 'admin',
            'services.proxmox.password' => 'secret',
            'services.proxmox.realm' => 'pam',
            'services.proxmox.node' => 'pve1',
        ]);
    }

    public function test_constructor_validates_required_config()
    {
        config(['services.proxmox.host' => '']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Proxmox configuration incomplete');

        new ProxmoxService();
    }

    public function test_authenticate_success()
    {
        Http::fake([
            '**/api2/json/access/ticket' => Http::response([
                'data' => [
                    'ticket' => 'test_ticket_123',
                    'CSRFPreventionToken' => 'test_csrf_123',
                ]
            ], 200),
        ]);

        $service = new ProxmoxService();
        $result = $service->authenticate();

        $this->assertTrue($result);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://pve.example.com/api2/json/access/ticket';
        });
    }

    public function test_authenticate_clears_password_after_success()
    {
        Http::fake([
            '**/api2/json/access/ticket' => Http::response([
                'data' => [
                    'ticket' => 'test_ticket_123',
                    'CSRFPreventionToken' => 'test_csrf_123',
                ]
            ], 200),
        ]);

        $service = new ProxmoxService();
        $service->authenticate();

        // Password should be cleared after auth
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('password');
        $property->setAccessible(true);
        $this->assertNull($property->getValue($service));
    }

    public function test_get_ticket_caches_ticket()
    {
        Http::fake([
            '**/api2/json/access/ticket' => Http::response([
                'data' => [
                    'ticket' => 'test_ticket_123',
                    'CSRFPreventionToken' => 'test_csrf_123',
                ]
            ], 200),
        ]);

        $service = new ProxmoxService();
        $service->authenticate();

        // Second call should use cached ticket without re-auth
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('getTicket');
        $method->setAccessible(true);
        $ticket = $method->invoke($service);

        $this->assertEquals('test_ticket_123', $ticket);
        // Verify only 1 HTTP request was made (not 2)
        Http::assertSentCount(1);
    }

    public function test_get_node_console_url_validates_host()
    {
        $service = new ProxmoxService();
        $url = $service->getNodeConsoleUrl();

        $this->assertStringContainsString('console=shell', $url);
        $this->assertStringContainsString('node=pve1', $url);
    }

    public function test_authenticate_handles_connection_failure()
    {
        Http::fake([
            '**/api2/json/access/ticket' => Http::response([], 401),
        ]);

        Log::shouldReceive('error')->once();

        $service = new ProxmoxService();
        $result = $service->authenticate();

        $this->assertFalse($result);
    }
}
