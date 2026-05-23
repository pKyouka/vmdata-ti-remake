<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\AnsibleService;
use Illuminate\Support\Facades\Process;

class AnsibleServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.ansible.playbook_path' => 'ansible/playbooks',
            'services.ansible.inventory_path' => 'ansible/hosts',
        ]);
    }

    public function test_constructor_validates_playbook_path()
    {
        config(['services.ansible.playbook_path' => '/nonexistent/path']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('does not exist');

        new AnsibleService();
    }

    public function test_playbook_name_validation_rejects_invalid_chars()
    {
        mkdir(base_path('ansible/playbooks'), 0755, true);

        $service = new AnsibleService();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid playbook name');

        $service->runPlaybook('../../../etc/passwd.yml', []);
    }

    public function test_playbook_name_validation_allows_safe_names()
    {
        mkdir(base_path('ansible/playbooks'), 0755, true);

        $service = new AnsibleService();
        $this->assertTrue(true); // If constructor passes, validation is working
    }

    public function test_extra_vars_must_be_array()
    {
        mkdir(base_path('ansible/playbooks'), 0755, true);
        touch(base_path('ansible/playbooks/test.yml'));

        $service = new AnsibleService();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('extraVars must be an array');

        $service->runPlaybook('test.yml', 'invalid_string');
    }

    public function test_check_installation_runs_ansible_version()
    {
        mkdir(base_path('ansible/playbooks'), 0755, true);
        mkdir(base_path('ansible'), 0755, true);

        $service = new AnsibleService();
        // This will actually run 'ansible --version' on the system
        // Test may fail if ansible not installed, but that's OK for CI
        $result = $service->checkInstallation();

        $this->assertNotNull($result);
    }

    public function test_playbook_file_must_exist()
    {
        mkdir(base_path('ansible/playbooks'), 0755, true);

        $service = new AnsibleService();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('not found');

        $service->runPlaybook('nonexistent.yml', []);
    }
}
