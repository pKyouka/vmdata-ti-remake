<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class AnsibleService
{
    protected $playbookPath;
    protected $inventoryPath;

    public function __construct()
    {
        $this->playbookPath = config('services.ansible.playbook_path', 'ansible/playbooks');
        $this->inventoryPath = config('services.ansible.inventory_path', 'ansible/hosts');
    }

    public function checkInstallation()
    {
        $process = Process::run('ansible --version');

        if ($process->successful()) {
            return $process->output();
        }

        return false;
    }

    public function runPlaybook($playbook, $extraVars = [])
    {
        $cmd = "ansible-playbook {$this->playbookPath}/{$playbook} -i {$this->inventoryPath}";

        if (!empty($extraVars)) {
            $vars = escapeshellarg(json_encode($extraVars));
            $cmd .= " --extra-vars {$vars}";
        }

        $process = Process::run($cmd);

        if ($process->successful()) {
            return $process->output();
        }

        Log::error("Ansible Playbook Failed: {$playbook}", ['output' => $process->output(), 'error' => $process->errorOutput()]);
        return false;
    }
}
