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

use Bailing\Annotation\EnumCodePrefix;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Coordinator\Constants;
use Hyperf\Coordinator\CoordinatorManager;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Stringable\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[Command]
class DevGenCodeCommand extends HyperfCommand
{
    public function __construct()
    {
        parent::__construct('dev:genCode');
    }

    public function configure()
    {
        parent::configure();
        $this->addArgument('className', InputArgument::REQUIRED, '类名');
        $this->addOption('dir', 'd', InputOption::VALUE_REQUIRED, '欲生成所在的目录名（例：输入Org会生成在app/Constants/I18n/Org）');
        $this->setDescription('生成code的代码（info自行补全）');
    }

    public function handle()
    {
        $this->line('开始生成!', 'info');

        $className = $this->input->getArgument('className');
        $className = ucfirst($className);
        if (! str_ends_with($className, 'Code')) {
            $className .= 'Code';
        }

        $dir = Str::ucfirst(trim(strval($this->input->getOption('dir'))));

        $stub = file_get_contents(__DIR__ . '/stubs/DevCode.stub');
        $stub = str_replace('%DIR_NAME%', $dir, $stub);
        $stub = str_replace('%CLASS_NAME%', $className, $stub);

        //得到类方法的所有注解
        $prefixCode = 100;
        $classes = AnnotationCollector::getClassesByAnnotation(EnumCodePrefix::class);
        foreach ($classes as $key => $value) {
            if (! str_contains($key, 'Bailing\Constants') && $value->prefixCode >= $prefixCode) {
                $prefixCode = $value->prefixCode + 1;
            }
        }

        $stub = str_replace('%PREFIX_CODE%', (string) $prefixCode, $stub);

        if (! file_exists(BASE_PATH . '/app/Constants/Code/' . $dir)) {
            mkdir(BASE_PATH . '/app/Constants/Code/' . $dir, 0777, true);
        }

        $fileName = BASE_PATH . '/app/Constants/Code/' . $dir . '/' . $className . '.php';
        file_put_contents($fileName, $stub);

        $this->line($fileName . '已生成，请自行补充里面的内容', 'info');

        CoordinatorManager::until(Constants::WORKER_EXIT)->resume();
    }
}
