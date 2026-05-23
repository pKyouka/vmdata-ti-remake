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

        // Convert relative paths to absolute
        if (!str_starts_with($this->playbookPath, '/')) {
            $this->playbookPath = base_path($this->playbookPath);
        }
        if (!str_starts_with($this->inventoryPath, '/')) {
            $this->inventoryPath = base_path($this->inventoryPath);
        }

        // Validate paths exist
        if (!is_dir($this->playbookPath)) {
            throw new \RuntimeException("Ansible playbook path does not exist: {$this->playbookPath}");
        }
        if (!is_dir($this->inventoryPath) && !file_exists($this->inventoryPath)) {
            throw new \RuntimeException("Ansible inventory path does not exist: {$this->inventoryPath}");
        }
    }

    public function checkInstallation()
    {
        try {
            $process = Process::timeout(10)->run('ansible --version');

            if ($process->successful()) {
                return $process->output();
            }

            Log::warning('Ansible check failed', [
                'exit_code' => $process->exitCode(),
                'stderr' => $process->errorOutput(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::warning('Ansible check error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function runPlaybook($playbook, $extraVars = [])
    {
        // Validate playbook name - allow only safe characters (alphanumeric, dash, underscore, dot)
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.ya?ml$/', $playbook)) {
            throw new \InvalidArgumentException("Invalid playbook name. Must be alphanumeric with .yml or .yaml extension: {$playbook}");
        }

        $playbookFile = "{$this->playbookPath}/{$playbook}";
        
        // Verify playbook file exists
        if (!file_exists($playbookFile)) {
            throw new \RuntimeException("Playbook not found: {$playbookFile}");
        }

        // Use Process with array arguments to avoid shell injection
        $args = [
            'ansible-playbook',
            $playbookFile,
            '-i',
            $this->inventoryPath,
        ];

        if (!empty($extraVars)) {
            // Validate extraVars is array or JSON-serializable
            if (!is_array($extraVars)) {
                throw new \InvalidArgumentException('extraVars must be an array');
            }
            
            $args[] = '--extra-vars';
            $args[] = json_encode($extraVars);
        }

        Log::info('Running Ansible playbook', [
            'playbook' => $playbook,
            'vars_count' => count($extraVars),
        ]);

        try {
            $process = Process::timeout(300)->run($args); // 5 minute timeout

            if ($process->successful()) {
                Log::info('Ansible playbook completed', ['playbook' => $playbook]);
                return $process->output();
            }

            Log::error("Ansible Playbook Failed: {$playbook}", [
                'exit_code' => $process->exitCode(),
                'stdout' => $process->output(),
                'stderr' => $process->errorOutput(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("Ansible Playbook Error: {$playbook}", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
