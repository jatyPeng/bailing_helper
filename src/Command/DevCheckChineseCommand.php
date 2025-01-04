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
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

#[Command]
class DevCheckChineseCommand extends HyperfCommand
{
    public function __construct()
    {
        parent::__construct('dev:check_chinese');
    }

    public function handle()
    {
        $notScanDir = [
            'app/Constants',
            'app/Dto',
            'app/Command',
        ];
        $scanRootPath = BASE_PATH . '/app';
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($scanRootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $hasChineseWordFile = [];

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = $file->getRealPath();

                foreach ($notScanDir as $item) {
                    if (str_contains($filePath, $item)) {
                        continue 2;
                    }
                }

                $code = file_get_contents($filePath);
                $codeWithoutComments = $this->removeComments($code);

                if ($containsChinese = $this->containsChinese($codeWithoutComments)) {
                    $key = 'app' . str_replace($scanRootPath, '', $filePath);
                    $hasChineseWordFile[$key] = $containsChinese;
                }
            }
        }

        stdLog()->info(sprintf('共计%d个文件中包含中文', count($hasChineseWordFile)));
        foreach ($hasChineseWordFile as $key => $item) {
            stdLog()->info($key, $item);
        }
    }

    protected function removeComments($code)
    {
        $patternArr = [
            '/\/\*.*?\*\//s', // 去除多行注释
            '/\/\/.*$/m', // 去除单行注释
            '/#\[(.*?)\]/m', // 去除PHP的#[]注解
            '/stdLog\(.*$/m', // 去除stdLog注释
            '/logger\(.*$/m', // 去除logger注释
            '/\$this->jobExecutorLogger->info\(.*$/m', // 去除xxlJob注释
        ];
        foreach ($patternArr as $pattern) {
            $code = preg_replace($pattern, '', $code);
        }
        return $code;
    }

    protected function containsChinese($string)
    {
        // 使用正则表达式检查是否包含中文字符
        $result = preg_match('/[\x{4e00}-\x{9fa5}]+/u', $string, $matches);
        if (empty($result)) {
            return [];
        }
        return $matches;
    }
}
