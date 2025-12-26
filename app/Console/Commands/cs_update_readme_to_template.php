<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Qcloud\Cos\Client;

class cs_update_readme_to_template extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cs:update-readme-to-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update README.md to template';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $templates = DB::table('liteapp_template')
                ->whereNull('deleted_at')
                ->get();

            $processedCount = 0;
            $errorCount = 0;

            foreach ($templates as $template) {
                try {
                    // 验证模板数据
                    if (!$this->validateTemplate($template)) {
                        $this->warn("跳过无效模板：ID {$template->id}");
                        continue;
                    }

                    // git命令拉取代码仓库中的README.md文件到本地
                    $this->gitPullReadmeFile($template);
                    
                    // 上传README.md文件到COS
                    $templateName = $template->name;
                    $publicPath = public_path();
                    $file = $publicPath . "/template/" . $template->id . "/README.md";
                    
                    if ($this->uploadReadme($templateName, $file)) {
                        // 使用参数化查询更新数据库 - 修复SQL注入风险
                        $cosPath = "cs-readme/" . $templateName . "/README.md";
                        $this->updateTemplateReadmeUrl($template->id, $cosPath);
                        $processedCount++;
                    } else {
                        $errorCount++;
                    }
                } catch (\Exception $e) {
                    $this->error("处理模板 {$template->name} 时出错：" . $e->getMessage());
                    Log::error("Template processing error", [
                        'template_id' => $template->id,
                        'template_name' => $template->name,
                        'error' => $e->getMessage()
                    ]);
                    $errorCount++;
                }
            }

            $this->info("处理完成：成功 {$processedCount} 个，失败 {$errorCount} 个");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("命令执行失败：" . $e->getMessage());
            Log::error("Command execution failed", ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }

    /**
     * 验证模板数据
     *
     * @param object $template
     * @return bool
     */
    private function validateTemplate(object $template): bool
    {
        return !empty($template->id) && 
               !empty($template->name) && 
               !empty($template->version_control_url) &&
               filter_var($template->version_control_url, FILTER_VALIDATE_URL);
    }

    /**
     * git命令拉取代码仓库中的README.md文件到本地
     *
     * @param object $template
     * @return void
     */
    private function gitPullReadmeFile(object $template): void
    {
        $gitRepository = $template->version_control_url;
        
        // 验证Git URL格式
        if (!filter_var($gitRepository, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("无效的Git仓库URL：{$gitRepository}");
        }

        // 根据模板ID生成目录
        $publicPath = public_path();
        $templatePath = $publicPath . "/template/" . $template->id;
        
        if (!is_dir($templatePath)) {
            if (!mkdir($templatePath, 0755, true)) {
                throw new \RuntimeException("无法创建目录：{$templatePath}");
            }
        }

        // 判断是否存在README.md文件，如果存在则跳过
        $readmeFile = $templatePath . "/README.md";
        if (file_exists($readmeFile)) {
            $this->info("README.md 已存在，跳过：{$template->name}");
            return;
        }

        // 安全地执行git命令 - 避免命令注入
        $this->executeGitCommands($templatePath, $gitRepository);
    }

    /**
     * 安全地执行Git命令
     *
     * @param string $templatePath
     * @param string $gitRepository
     * @return void
     */
    private function executeGitCommands(string $templatePath, string $gitRepository): void
    {
        $commands = [
            ['git', 'init'],
            ['git', 'remote', 'add', 'origin', $gitRepository],
            ['git', 'fetch', '--depth', '1', 'origin'],
            ['git', 'checkout', 'origin/master', '--', 'README.md']
        ];

        foreach ($commands as $command) {
            $process = new \Symfony\Component\Process\Process($command, $templatePath);
            $process->setTimeout(60); // 设置超时时间
            
            try {
                $process->mustRun();
            } catch (\Symfony\Component\Process\ProcessFailedException $e) {
                // 如果master分支不存在，尝试main分支
                if (str_contains($e->getMessage(), 'master') && $command[0] === 'git' && $command[1] === 'checkout') {
                    $command[2] = 'origin/main';
                    $process = new \Symfony\Component\Process\Process($command, $templatePath);
                    $process->setTimeout(60);
                    $process->mustRun();
                } else {
                    throw $e;
                }
            }
        }
    }

    /**
     * 上传README文件到COS
     *
     * @param string $templateName
     * @param string $file
     * @return bool
     */
    private function uploadReadme(string $templateName, string $file): bool
    {
        if (!file_exists($file)) {
            $this->warn("README.md 文件不存在：{$file}");
            return false;
        }

        // 从配置文件获取COS配置 - 修复硬编码凭据问题
        $cosConfig = config('filesystems.disks.cos');
        if (!$cosConfig) {
            $this->error("COS配置未找到，请检查config/filesystems.php");
            return false;
        }

        try {
            $cosClient = new Client([
                'region' => $cosConfig['region'],
                'schema' => 'https',
                'credentials' => [
                    'secretId' => $cosConfig['secret_id'],
                    'secretKey' => $cosConfig['secret_key']
                ]
            ]);

            $key = "cs-readme/" . $templateName . "/README.md";

            $result = $cosClient->putObject([
                'Bucket' => $cosConfig['bucket'],
                'Key' => $key,
                'Body' => fopen($file, 'rb')
            ])->toArray();

            if (!isset($result['ETag']) || empty($result['ETag'])) {
                $this->error("README.md 文件上传失败：{$templateName}");
                return false;
            }

            $this->info("README.md 文件上传成功：{$templateName}");
            return true;

        } catch (\Exception $e) {
            $this->error("README.md 文件上传失败：{$templateName} - " . $e->getMessage());
            
            // 记录错误日志
            Log::error("COS upload failed", [
                'template_name' => $templateName,
                'file' => $file,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * 使用参数化查询更新模板README URL - 修复SQL注入风险
     *
     * @param int $templateId
     * @param string $cosPath
     * @return void
     */
    private function updateTemplateReadmeUrl(int $templateId, string $cosPath): void
    {
        try {
            // 使用参数化查询替代字符串拼接 - 安全修复
            $affected = DB::table('liteapp_template')
                ->where('id', $templateId)
                ->update(['readme_url' => $cosPath]);

            if ($affected > 0) {
                $this->info("数据库更新成功：模板ID {$templateId}");
            } else {
                $this->warn("数据库更新失败：模板ID {$templateId} 不存在");
            }

            // 记录SQL操作到文件（用于审计）
            $this->logSqlOperation($templateId, $cosPath);

        } catch (\Exception $e) {
            $this->error("数据库更新失败：" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 记录SQL操作到文件
     *
     * @param int $templateId
     * @param string $cosPath
     * @return void
     */
    private function logSqlOperation(int $templateId, string $cosPath): void
    {
        $logPath = storage_path('logs/update_readme_to_template.sql');
        $logContent = sprintf(
            "-- %s\n-- 模板ID: %d, COS路径: %s\nUPDATE liteapp_template SET readme_url = '%s' WHERE id = %d;\n\n",
            now()->format('Y-m-d H:i:s'),
            $templateId,
            $cosPath,
            addslashes($cosPath),
            $templateId
        );

        File::append($logPath, $logContent);
    }
}
