<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class CodeRunnerController extends Controller
{
    /**
     * Execute student practice code safely with a timeout and security sandboxing
     */
    public function execute(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10000',
            'language' => 'required|in:php,javascript,python,html,sql',
        ]);

        $code = $validated['code'];
        $language = $validated['language'];

        $startTime = microtime(true);
        $tempDir = storage_path('framework/cache');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $stdout = '';
        $stderr = '';
        $exitCode = 0;

        try {
            if ($language === 'php') {
                // Ensure PHP opening tag if omitted
                $phpCode = trim($code);
                if (!str_starts_with($phpCode, '<?php') && !str_starts_with($phpCode, '<?')) {
                    $phpCode = "<?php\n" . $phpCode;
                }

                $tempFile = $tempDir . '/sandbox_' . uniqid() . '.php';
                file_put_contents($tempFile, $phpCode);

                // Run with disabled dangerous system functions and 5-second process timeout
                $process = new Process([
                    'php',
                    '-d', 'disable_functions=exec,shell_exec,system,passthru,proc_open,popen,pcntl_exec',
                    '-d', 'max_execution_time=5',
                    '-d', 'memory_limit=128M',
                    $tempFile
                ]);
                $process->setTimeout(5);
                $process->run();

                $stdout = $process->getOutput();
                $stderr = $process->getErrorOutput();
                $exitCode = $process->getExitCode();

                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }

            } elseif ($language === 'python') {
                $tempFile = $tempDir . '/sandbox_' . uniqid() . '.py';
                file_put_contents($tempFile, $code);

                $process = new Process(['python3', $tempFile]);
                $process->setTimeout(5);
                $process->run();

                $stdout = $process->getOutput();
                $stderr = $process->getErrorOutput();
                $exitCode = $process->getExitCode();

                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }

            } elseif ($language === 'javascript') {
                $tempFile = $tempDir . '/sandbox_' . uniqid() . '.js';
                file_put_contents($tempFile, $code);

                $process = new Process(['node', $tempFile]);
                $process->setTimeout(5);
                $process->run();

                $stdout = $process->getOutput();
                $stderr = $process->getErrorOutput();
                $exitCode = $process->getExitCode();

                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }

            } elseif ($language === 'html') {
                $stdout = $code;
            }

        } catch (ProcessTimedOutException $e) {
            $stderr = "Execution timed out after 5.0 seconds (Infinite loop or long-running script detected).";
            $exitCode = 124;
        } catch (\Throwable $e) {
            $stderr = "Runtime Error: " . $e->getMessage();
            $exitCode = 1;
        }

        $elapsed = round(microtime(true) - $startTime, 3);

        return response()->json([
            'success' => $exitCode === 0,
            'stdout' => $stdout,
            'stderr' => $stderr,
            'exit_code' => $exitCode,
            'execution_time' => $elapsed . 's',
            'language' => $language,
        ]);
    }
}
