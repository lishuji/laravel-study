<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Qcloud\Cos\Client;


class cs_update_readme_to_template extends Command
{
    // COS配置，定义常量
    private const secretId = "";
    private const secretKey = "";
    private const region = "";
    private const bucket = "";

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
    public function handle()
    {
        $templates = DB::table('liteapp_template')->whereNull('deleted_at')->get();
        foreach ($templates as $template) {
            // git命令拉取代码仓库中的README.md文件到本地
            $this->gitPullReadmeFile($template);
            // 上传README.md文件到COS
            $templateName = $template->name;
            $publicPath = public_path();
            $file = $publicPath . "/template/" . $template->id . "/README.md";
            $this->uploadReadme($templateName, $file);
            // 拼接SQL语句
            $cosPath = "cs-readme/" . $templateName . "/README.md";
            $this->combinedSql($template, $cosPath);
        }

        return 0;
    }

    // git命令拉取代码仓库中的README.md文件到本地
    private function gitPullReadmeFile($template): void
    {
        $gitRepository = $template->version_control_url;
        // 根据模板ID生成目录
        $publicPath = public_path();
        $templatePath = $publicPath . "/template/" . $template->id;
        if (!is_dir($templatePath)) {
            mkdir($templatePath, 0777, true);
        }
        // 判断是否存在README.md文件，如果存在则跳过
        $readmeFile = $templatePath . "/README.md";
        if (file_exists($readmeFile)) {
            return;
        }
        // 执行拉取README.md文件的git命令
        $cmds = [
            "cd " . $templatePath,
            "git init",
            "git remote add origin " . $gitRepository,
            "git fetch --depth 1 origin",
            "git checkout origin/master -- README.md"
        ];
        $cmd = implode(" && ", $cmds);
        exec($cmd);
    }


    private function uploadReadme($templateName, $file): void
    {
        $cosClient = new Client(
            array(
                'region' => self::region,
                'schema' => 'https',
                'credentials' => array(
                    'secretId' => self::secretId,
                    'secretKey' => self::secretKey
                )
            )
        );

        $key = "cs-readme/" . $templateName . "/README.md"; // COS存储路径

        try {
            $result = $cosClient->putObject(array(
                'Bucket' => self::bucket,
                'Key' => $key,
                'Body' => fopen($file, 'rb')
            ))->toArray();
            // 请求成功
            if (!isset($result['ETag']) || $result['ETag'] == "") {
                $this->error("README.md 文件上传失败：" . $templateName);
                return;
            }

            $this->info("README.md 文件上传成功：" . $templateName);
        } catch (\Exception $e) {
            // 请求失败,记录错误日志
            $this->error("README.md 文件上传失败：" . $templateName);
            // 记录错误日志,写成文件
            $errorLog = date('Y-m-d H:i:s') . " README.md 文件上传失败：" . $templateName . "\n";
            $file = fopen('update_readme_to_template_error.log', 'a');
            fwrite($file, $errorLog);
            fclose($file);
        }
    }

    private function combinedSql($template, $cosPath)
    {
        $templateName = $template->name;
        $templateId = $template->id;
        $sql = "UPDATE liteapp_template SET readme_url = '{$cosPath}' WHERE id = {$templateId};\n";
        $this->info("开始写入 SQL 文件：{$templateName}");
        // 追加 SQL 语句到文件
        $file = fopen('update_readme_to_template.sql', 'a');
        fwrite($file, $sql);
        fclose($file);
    }
}
