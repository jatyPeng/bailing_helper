<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Job;

use Bailing\Annotation\XxlJobTask;
use Hyperf\Di\Annotation\Inject;
use Hyperf\XxlJob\Annotation\XxlJob;
use Hyperf\XxlJob\Handler\AbstractJobHandler;
use Hyperf\XxlJob\Logger\JobExecutorLoggerInterface;
use Hyperf\XxlJob\Requests\RunRequest;

/**
 * 缓存文件的清空（日志）.
 */
#[XxlJob('planRuntimeFileClear')]
#[XxlJobTask(jobDesc: '缓存文件的清空（日志）', cron: '0 0/1 * * * ?', jobHandler: 'planRuntimeFileClear')]
class RuntimeFileClearJob extends AbstractJobHandler
{
    #[Inject]
    protected JobExecutorLoggerInterface $jobExecutorLogger;

    /**
     * 执行任务.
     */
    public function execute(RunRequest $request): void
    {
        var_dump(BASE_PATH);
    }
}
