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
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;

#[Command]
class GenCURDCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('gen:curd');
    }

    public function configure()
    {
        parent::configure();
        $this->addOption('dir', 'd', InputOption::VALUE_REQUIRED, '欲生成控制器的子目录名（例：Org）');
        $this->addOption('model', 'm', InputOption::VALUE_REQUIRED, '已存在Model的类名（例：User）');
        $this->setDescription('生成CRUD代码');
    }

    public function handle()
    {
        $this->line('调用crud!', 'info');

        $dir = $this->input->getOption('dir');
        $model = $this->input->getOption('model');

        $this->call('gen:crud', [
            '--dir' => $dir,
            '--model' => $model,
        ]);
    }
}
