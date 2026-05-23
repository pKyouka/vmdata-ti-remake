<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxmoxService
{
    protected $host;
    protected $username;
    protected $password;
    protected $realm;
    protected $node;
    protected $ticket;
    protected $csrfToken;
    protected $ticketExpiry;

    public function __construct()
    {
        $this->host = config('services.proxmox.host');
        $this->username = config('services.proxmox.username');
        $this->password = config('services.proxmox.password');
        $this->realm = config('services.proxmox.realm');
        $this->node = config('services.proxmox.node');
        $this->ticketExpiry = 0;

        // Validate required configuration
        if (!$this->host || !$this->username || !$this->password || !$this->realm || !$this->node) {
            throw new \RuntimeException(
                'Proxmox configuration incomplete. Ensure PROXMOX_HOST, PROXMOX_USERNAME, '
                . 'PROXMOX_PASSWORD, PROXMOX_REALM, and PROXMOX_NODE are set in .env'
            );
        }
    }

    /**
     * Get cached ticket if still valid, otherwise authenticate
     */
    private function getTicket()
    {
        if ($this->ticket && time() < $this->ticketExpiry) {
            return $this->ticket;
        }
        
        if ($this->authenticate()) {
            return $this->ticket;
        }
        
        return null;
    }

    public function authenticate()
    {
        try {
            $response = Http::timeout(5)
                ->retry(2, 100)
                ->post("{$this->host}/api2/json/access/ticket", [
                    'username' => "{$this->username}@{$this->realm}",
                    'password' => $this->password,
                ]);

            if ($response->successful()) {
                $data = $response->json()['data'];
                $this->ticket = $data['ticket'];
                $this->csrfToken = $data['CSRFPreventionToken'];
                // Proxmox tickets expire after 2 hours; cache for 90 minutes
                $this->ticketExpiry = time() + (90 * 60);
                
                // Clear plaintext password after authentication
                $this->password = null;
                
                return true;
            }

            Log::error('Proxmox Authentication Failed', [
                'status' => $response->status(),
                'host' => $this->host,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Proxmox Connection Error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function checkConnection()
    {
        return $this->authenticate();
    }

    public function getNodeStatus()
    {
        $ticket = $this->getTicket();
        if (!$ticket) {
            Log::warning('Proxmox: Unable to obtain valid ticket');
            return null;
        }

        try {
            $response = Http::timeout(5)
                ->retry(2, 100)
                ->withHeaders(['Cookie' => "PVEAuthCookie={$ticket}"])
                ->get("{$this->host}/api2/json/nodes/{$this->node}/status");

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }

            Log::error('Proxmox getNodeStatus failed', [
                'status' => $response->status(),
                'node' => $this->node,
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Proxmox API error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get console URL for the node
     * Note: This returns link to Proxmox web UI console, not an embedded terminal
     */
    public function getNodeConsoleUrl()
    {
        if (!$this->host || !$this->node) {
            return null;
        }

        // Validate host is a valid URL/domain
        if (filter_var($this->host, FILTER_VALIDATE_URL) === false) {
            Log::warning('Proxmox host is not a valid URL', ['host' => $this->host]);
            return null;
        }

        return "{$this->host}/?console=shell&node={$this->node}&vmid=0";
    }

    public function __destruct()
    {
        // Clear sensitive data when object destroyed
        $this->password = null;
        $this->ticket = null;
        $this->csrfToken = null;
    }
}
