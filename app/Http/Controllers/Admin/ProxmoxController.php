<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProxmoxService;
use App\Services\AnsibleService;
use Illuminate\Http\Request;

class ProxmoxController extends Controller
{
    protected $proxmoxService;
    protected $ansibleService;

    public function __construct(ProxmoxService $proxmoxService, AnsibleService $ansibleService)
    {
        $this->proxmoxService = $proxmoxService;
        $this->ansibleService = $ansibleService;
    }

    public function index()
    {
        $proxmoxStatus = $this->proxmoxService->getNodeStatus();
        $ansibleStatus = $this->ansibleService->checkInstallation();

        // Simulating terminal check
        $terminalUrl = null;
        if ($proxmoxStatus) {
            $terminalUrl = $this->proxmoxService->getNodeConsoleUrl();
        }

        return view('admin.proxmox.index', compact('proxmoxStatus', 'ansibleStatus', 'terminalUrl'));
    }

    public function checkConnection()
    {
        $status = $this->proxmoxService->checkConnection();
        return response()->json(['connected' => $status]);
    }
}
