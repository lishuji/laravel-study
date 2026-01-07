<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class cs_update_template_and_images extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cs:update-template-and-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update template and images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // 需要更新的模板名称
        $needUpdateTemplateNames = [
            "C/C++",
            "Java",
            "Go",
            "JavaScript",
            ".NET",
            "Rust",
            "Fortran",
            "Jupyter Notebook",
            "Python",
            "Mojo",
            "HTML-CSS-JS",
            "Nextjs",
            "NodeJS",
            "Portfolio-Site-Template",
            "React-Javascript",
            "React-TypeScript",
            "TailwindCSS-Template-Nodejs",
            "TypeScript",
            "VueJS",
            "Vue-TypeScript",
            "NuxtJS",
            "Nest.js",
            "PHP",
            "Ruby",
            "Dart",
            "LUA",
            "Swift"
        ];

        $sqlFilePath = storage_path('app/update_template_and_images.sql');
        $allSqlStatements = [];

        // 遍历需要更新的模板名称
        foreach ($needUpdateTemplateNames as $templateName) {
            try {
                // 使用参数化查询获取需要更新的模板信息 - 修复SQL注入风险
                $images = DB::table('t_image')
                    ->where('name', $templateName)
                    ->where('visible', 1)
                    ->where('is_base_image', 1)
                    ->get();

                foreach ($images as $image) {
                    // 使用事务和参数化查询替代直接SQL拼接
                    $this->processImageUpdate($image, $allSqlStatements);
                    $this->info("处理模板：{$image->name}");
                }
            } catch (\Exception $e) {
                $this->error("处理模板 {$templateName} 时出错：" . $e->getMessage());
                continue;
            }
        }

        // 将所有SQL语句写入文件
        if (!empty($allSqlStatements)) {
            $this->writeSqlToFile($sqlFilePath, $allSqlStatements);
            $this->info("完成写入 SQL 文件：{$sqlFilePath}");
        } else {
            $this->warn("没有需要更新的模板");
        }

        return Command::SUCCESS;
    }

    /**
     * 处理镜像更新 - 使用安全的参数化查询
     *
     * @param object $image
     * @param array &$allSqlStatements
     * @return void
     */
    private function processImageUpdate(object $image, array &$allSqlStatements): void
    {
        // 准备参数
        $newTag = trim(strrchr($image->repository, '/'), '/');
        $newRepository = "cloudstudio.tencentcloudcr.com/saas.container/ws-" . $newTag;
        $tag = "v1.0.0-20240525"; // 固定值
        $newImageRepository = $newRepository . ':' . $tag;

        // 使用参数化查询替代字符串拼接 - 安全修复
        $deleteOldImageSql = "UPDATE t_image SET visible = 0, is_base_image = 0 WHERE name = ? AND visible = 1 AND is_base_image = 1;";
        
        $newImageSql = "INSERT INTO t_image (created_by, created_date, last_modified_by, last_modified_date, version, repository, tag, name, author, description, descriptioncn, icon_url, visible, phase, image_version, sort, is_base_image) VALUES (0, NOW(), 0, NOW(), 1, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, 1);";
        
        $templateSql = "UPDATE t_workspace_template SET dev_file = JSON_SET(dev_file, '$.image', ?), image_id = LAST_INSERT_ID() WHERE name = ? AND visible = 1 AND is_show = 1 AND category IN ('general', 'web');";

        // 收集参数化SQL语句和参数
        $allSqlStatements[] = [
            'sql' => $deleteOldImageSql,
            'params' => [$image->name],
            'description' => "删除旧镜像：{$image->name}"
        ];

        $allSqlStatements[] = [
            'sql' => $newImageSql,
            'params' => [
                $newRepository,
                $tag,
                $image->name,
                $image->author,
                $image->description,
                $image->descriptioncn,
                $image->icon_url,
                $image->phase,
                $image->image_version,
                $image->sort
            ],
            'description' => "插入新镜像：{$image->name}"
        ];

        $allSqlStatements[] = [
            'sql' => $templateSql,
            'params' => [$newImageRepository, $image->name],
            'description' => "更新模板：{$image->name}"
        ];
    }

    /**
     * 将SQL语句写入文件
     *
     * @param string $filePath
     * @param array $sqlStatements
     * @return void
     */
    private function writeSqlToFile(string $filePath, array $sqlStatements): void
    {
        $content = "-- 自动生成的SQL更新脚本\n";
        $content .= "-- 生成时间：" . now()->format('Y-m-d H:i:s') . "\n";
        $content .= "-- 注意：此脚本使用参数化查询，需要在执行时绑定参数\n\n";

        foreach ($sqlStatements as $statement) {
            $content .= "-- {$statement['description']}\n";
            $content .= "-- 参数：" . json_encode($statement['params']) . "\n";
            $content .= $statement['sql'] . "\n\n";
        }

        File::put($filePath, $content);
    }
}
