<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Database\Schema\Schema;
use Hyperf\Stringable\Str;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[Command]
class GenCRUDCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('gen:crud');
    }

    public function configure()
    {
        parent::configure();
        $this->addArgument('table', InputArgument::REQUIRED, '数据表名');
        $this->addOption('dir', 'd', InputOption::VALUE_OPTIONAL, '欲生成控制器所在的目录名（例：输入Org会生成在app/Controller/Org）');
        $this->setDescription('生成CRUD代码');
    }

    public function handle()
    {
        $this->line('开始生成!', 'info');

        $dir = Str::ucfirst(trim(strval($this->input->getOption('dir'))));
        if (empty($dir)) {
            $dir = 'Org';
        }

        // 引入model
        $table = $this->input->getArgument('table');
        $model = Str::ucfirst(Str::camel(trim($table)));
        $tmp = 'App\Model\\' . $model;
        if (! class_exists($tmp)) {
            $this->line($model . ' Model不存在', 'error');
            return;
        }
        $class = new $tmp();
        if (! method_exists($class, 'trimFields')) {
            $this->line($model . ' Model 的 trimFields 方法不存在，请增加', 'error');
            return;
        }

        // 生成Controller
        $stub = file_get_contents(__DIR__ . '/stubs/Controller.stub');
        $stub = $this->replaceClass($stub, $model);
        $stub = $this->replaceDir($stub, $dir);
        $stub = $this->replaceRoute($stub, $dir, $model);
        $stub = $this->replaceControllerFields($stub, $table, $class::trimFields());
        $controllerDir = $this->makeDirectory(BASE_PATH . '/app/Controller/' . $dir);
        file_put_contents($controllerDir . '/' . $model . 'Controller.php', $stub);

        // 生成Request
        $stub = file_get_contents(__DIR__ . '/stubs/Request.stub');
        $stub = $this->replaceClass($stub, $model);
        $stub = $this->replaceTrimFields($stub, $class::trimFields());
        $stub = $this->replaceRequestFields($stub, $table, $class::trimFields());
        $requestDir = $this->makeDirectory(BASE_PATH . '/app/Request');
        file_put_contents($requestDir . '/' . $model . 'Request.php', $stub);
    }

    protected function makeDirectory(string $path): string
    {
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        return $path;
    }

    protected function replaceClass(string $stub, string $name): string
    {
        return str_replace('%CLASS%', $name, $stub);
    }

    protected function replaceDir(string $stub, string $dir): string
    {
        $dir = str_replace('/', '\\', $dir);
        return str_replace('%DIR%', $dir, $stub);
    }

    protected function replaceRoute(string $stub, string $dir, string $model): string
    {
        $route = '/' . Str::snake($dir, '/') . '/' . Str::snake($model, '/');
        return str_replace('%ROUTER%', $route, $stub);
    }

    protected function replaceTrimFields(string $stub, array $fields): string
    {
        $str = implode("', '", $fields);
        $str = "'" . $str . "'";
        return str_replace('%TRIM_FIELDS%', $str, $stub);
    }

    protected function replaceControllerFields(string $stub, string $table, array $fields): string
    {
        $columns = Schema::getColumnListing($table);
        $columnsTypeArr = array_column(Schema::getColumnTypeListing($table), null, 'column_name');
        if ($columns) {
            // 控制器的表单
            $addFields = '';
            foreach ($columns as $item) {
                if (! in_array($item, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    if ($item == 'org_id') {
                        $addFields .= '$model->org_id = $nowAdmin->org_id;' . PHP_EOL . '        ';
                    } elseif (in_array($item, $fields)) {
                        $addFields .= '$model->' . $item . ' = $post[\'' . $item . '\'];' . PHP_EOL . '        ';
                    } else {
                        if (in_array($columnsTypeArr[$item]['data_type'], ['int', 'smallint', 'mediumint', 'tinyint'])) {
                            $addFields .= 'isset($post[\'' . $item . '\']) && $model->' . $item . ' = (int) $post[\'' . $item . '\'];' . PHP_EOL . '        ';
                        } else {
                            $addFields .= 'isset($post[\'' . $item . '\']) && $model->' . $item . ' = $post[\'' . $item . '\'];' . PHP_EOL . '        ';
                        }
                    }
                }
            }
            $stub = str_replace('%ADD_FIELDS%', trim($addFields), $stub);

            $editFields = '';
            foreach ($columns as $item) {
                if (! in_array($item, ['id', 'org_id', 'created_at', 'updated_at', 'deleted_at'])) {
                    if (in_array($item, $fields)) {
                        $editFields .= '$detail->' . $item . ' = $post[\'' . $item . '\'];' . PHP_EOL . '        ';
                    } else {
                        if (in_array($columnsTypeArr[$item]['data_type'], ['int', 'smallint', 'mediumint', 'tinyint', 'bigint'])) {
                            $editFields .= 'isset($post[\'' . $item . '\']) && $detail->' . $item . ' = (int) $post[\'' . $item . '\'];' . PHP_EOL . '        ';
                        } else {
                            $editFields .= 'isset($post[\'' . $item . '\']) && $detail->' . $item . ' = $post[\'' . $item . '\'];' . PHP_EOL . '        ';
                        }
                    }
                }
            }
            $stub = str_replace('%EDIT_FIELDS%', trim($editFields), $stub);
        }
        return $stub;
    }

    protected function replaceRequestFields(string $stub, string $table, array $fields): string
    {
        $columns = Schema::getColumnListing($table);
        $columnsTypeArr = array_column(Schema::getColumnTypeListing($table), null, 'column_name');
        if ($columns) {
            $requestFields = '';
            foreach ($columns as $item) {
                if (! in_array($item, ['id', 'org_id', 'created_at', 'updated_at', 'deleted_at'])) {
                    if (in_array($item, $fields)) {
                        $length = (str_replace([$columnsTypeArr[$item]['data_type'], '(', ')'], '', $columnsTypeArr[$item]['column_type']));
                        $requestFields .= "'" . $item . "' => 'required|max:" . $length . "'," . PHP_EOL . '            ';
                    } elseif (in_array($columnsTypeArr[$item]['data_type'], ['int', 'smallint', 'mediumint'])) {
                        $requestFields .= "'" . $item . "' => 'integer'," . PHP_EOL . '            ';
                    } elseif (in_array($columnsTypeArr[$item]['data_type'], ['tinyint'])) {
                        $requestFields .= "'" . $item . "' => 'integer|lte:128'," . PHP_EOL . '            ';
                    } elseif (in_array($columnsTypeArr[$item]['data_type'], ['timestamp', 'datetime', 'date'])) {
                        $requestFields .= "'" . $item . "' => 'date'," . PHP_EOL . '            ';
                    }
                }
            }
            $stub = str_replace('%REQUEST_RULES%', trim($requestFields), $stub);
        }
        return $stub;
    }
}
