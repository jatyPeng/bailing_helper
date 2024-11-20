<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Aspect;

use Bailing\Helper\TranslationHelper;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

#[Aspect]
class TranslationAspect extends AbstractAspect
{
    // 要切入的类或 Trait，可以多个，亦可通过 :: 标识到具体的某个方法，通过 * 可以模糊匹配
    public array $classes = [
        'Hyperf\Database\Model\Builder::get',
    ];

    // 要切入的注解，具体切入的还是使用了这些注解的类，仅可切入类注解和类方法注解
    public array $annotations = [];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $result = $proceedingJoinPoint->process();
        if (empty($result)) {
            return $result;
        }

        $newResult = $result->all();
        if (empty($newResult)) {
            return $result;
        }

        // 得到需要i18n的配置项
        $tableName = $result->offsetGet(0)->getTable();
        $tableI18nConfig = config('translation.i18n_table.' . $tableName);
        if (empty($tableI18nConfig)) {
            return $result;
        }

        // 得到i8n的值
        $resultArr = $result->toArray();
        $relationField = $tableI18nConfig['relation'] ?? 'id';
        $i18nResult = TranslationHelper::i18nConvert(
            $resultArr,
            $tableName,
            $tableI18nConfig['i18n'],
            $relationField,
            ! empty($tableI18nConfig['isOrg']) ? $resultArr[0]['org_id'] ?? 0 : 0
        );
        $i18nResult = array_column($i18nResult, null, $relationField);

        // 重组i18n的结果
        foreach ($newResult as $model) {
            if ($model instanceof Model) {
                foreach ($tableI18nConfig['i18n'] as $item) {
                    $tmpField = 'i18n_' . $item;
                    if (! empty($i18nResult[$model->{$relationField}][$tmpField])) {
                        $model->{$tmpField} = $i18nResult[$model->{$relationField}][$tmpField];
                    }
                }
            }
        }

        return new Collection($newResult);
    }
}
