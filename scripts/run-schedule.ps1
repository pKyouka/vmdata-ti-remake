<#
Runs Laravel scheduler once and logs the output to storage/logs/scheduler-YYYYMMDD.log

Usage: 
  PowerShell -NoProfile -ExecutionPolicy Bypass -File scripts\run-schedule.ps1

Notes:
 - Ensure `php` is available on PATH or set $PhpBinary to the absolute path to php.exe
 - The script writes to storage/logs which should be writable by the user executing the task
#>

param()

try {
    # Determine project root (one level up from this scripts folder)
    $scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
    $projectRoot = Resolve-Path (Join-Path $scriptDir '..')
    Set-Location $projectRoot

    # PHP binary - prefer environment php, otherwise user can set full path here
    $PhpBinary = 'php'

    $logDir = Join-Path $projectRoot 'storage\logs'
    if (-not (Test-Path $logDir)) { New-Item -ItemType Directory -Path $logDir -Force | Out-Null }

    $logFile = Join-Path $logDir ("scheduler-{0}.log" -f (Get-Date -Format yyyyMMdd))

    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    Add-Content -Path $logFile -Value "[$timestamp] Running artisan schedule:run"

    # Run the schedule:run command and capture output
    $psi = New-Object System.Diagnostics.ProcessStartInfo
    $psi.FileName = $PhpBinary
    $psi.Arguments = 'artisan schedule:run'
    $psi.RedirectStandardOutput = $true
    $psi.RedirectStandardError = $true
    $psi.UseShellExecute = $false
    $psi.CreateNoWindow = $true

    $proc = New-Object System.Diagnostics.Process
    $proc.StartInfo = $psi
    $proc.Start() | Out-Null

    $stdOut = $proc.StandardOutput.ReadToEnd()
    $stdErr = $proc.StandardError.ReadToEnd()
    $proc.WaitForExit()

    if ($stdOut) { Add-Content -Path $logFile -Value $stdOut }
    if ($stdErr) { Add-Content -Path $logFile -Value "ERROR:`n$stdErr" }

    $timestampEnd = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    Add-Content -Path $logFile -Value "[$timestampEnd] Done (ExitCode: $($proc.ExitCode))`n"
    exit $proc.ExitCode
}
catch {
    $errLog = Join-Path (Split-Path -Parent $MyInvocation.MyCommand.Definition) '..\storage\logs\scheduler-error.log'
    Add-Content -Path $errLog -Value "$(Get-Date -Format o) - Exception: $($_.Exception.Message)`n$($_.Exception.StackTrace)"
    throw
}
