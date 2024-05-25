<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
    public function handle()
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
        // 遍历需要更新的模板名称
        foreach ($needUpdateTemplateNames as $templateName) {
            $allSql = ''; // 所有SQL语句，用于写入文件
            // 获取需要更新的模板信息
            DB::table('t_image')->where([
                'name' => $templateName,
                'visible' => 1,
                'is_base_image' => 1
            ])->get()->each(function ($image) use (&$allSql) {
                // 组合软删除旧镜像信息
                $deleteOldImageSql = "UPDATE t_image SET visible = 0,is_base_image = 0 WHERE name = '{$image->name}' AND visible = 1 AND is_base_image = 1;\n";
                // 组合新镜像信息
                $newTag = trim(strrchr($image->repository, '/'), '/');
                $newRepository = "cloudstudio.tencentcloudcr.com/saas.container/ws-" . $newTag;
                $tag = "v1.0.0-20240525"; // 固定值
                $newImageSql = "INSERT INTO t_image (created_by, created_date, last_modified_by, last_modified_date, version, repository,
                     tag, name, author, description, descriptioncn, icon_url, visible, phase, image_version, sort,
                     is_base_image) VALUES (0,now(),0,now(),1,'{$newRepository}','{$tag}','{$image->name}','{$image->author}',
                        '{$image->description}','{$image->descriptioncn}','{$image->icon_url}',1,'{$image->phase}','{$image->image_version}',
                        '{$image->sort}',1);\n";
                // 更新模板镜像
                $newImageRepository = $newRepository . ':' . $tag; // 新镜像地址：cloudstudio.tencentcloudcr.com/saas.container/ws-ubuntu-20.04:v1.0.0-20240525
                $templateSql = "UPDATE t_workspace_template SET dev_file = JSON_SET(dev_file, '$.image', '{$newImageRepository}'), image_id = LAST_INSERT_ID() WHERE name = '{$image->name}' AND visible = 1 AND is_show = 1 and category in ('general', 'web');\n";

                // 拼接SQL语句
                $allSql .= $deleteOldImageSql . $newImageSql . $templateSql . "\n"; // 每个模板一条SQL语句

                $this->info("开始写入 SQL 文件：{$image->name}");
                // 追加 SQL 语句到文件
                $file = fopen('update_template_and_images.sql', 'a');
                fwrite($file, $allSql);
                fclose($file);
            });
        }

        // 完成提示
        $this->info("完成写入 SQL 文件");

        return Command::SUCCESS;
    }
}
