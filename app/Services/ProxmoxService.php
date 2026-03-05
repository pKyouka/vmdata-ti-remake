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

    public function __construct()
    {
        $this->host = config('services.proxmox.host');
        $this->username = config('services.proxmox.username');
        $this->password = config('services.proxmox.password');
        $this->realm = config('services.proxmox.realm');
        $this->node = config('services.proxmox.node');
    }

    public function authenticate()
    {
        try {
            $response = Http::withoutVerifying()->post("{$this->host}/api2/json/access/ticket", [
                'username' => "{$this->username}@{$this->realm}",
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'];
                $this->ticket = $data['ticket'];
                $this->csrfToken = $data['CSRFPreventionToken'];
                return true;
            }

            Log::error('Proxmox Authentication Failed', ['response' => $response->body()]);
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
        if (!$this->authenticate()) {
            return null;
        }

        $response = Http::withoutVerifying()
            ->withHeaders(['Cookie' => "PVEAuthCookie={$this->ticket}"])
            ->get("{$this->host}/api2/json/nodes/{$this->node}/status");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return null;
    }

    // Get console for the NODE itself
    public function getNodeConsoleUrl()
    {
        // PVE uses xterm.js implementation.
        // We can redirect user to the PVE web interface console if appropriate
        // Or generate a VNC/xterm console URL.
        // simpler approach: Return link to proxmox shell in PVE UI for now,
        // or just verify we CAN get a console token (integrating xterm.js in this app is complex).

        // However, user said "enter the terminal".
        // Let's at least provide a direct link to the node shell if possible or just show status.
        // Opening a terminal usually requires a WebSocket connection which might be advanced.
        // For now, let's just return the check result.

        return "{$this->host}/?console=shell&node={$this->node}&vmid=0";
    }
}
