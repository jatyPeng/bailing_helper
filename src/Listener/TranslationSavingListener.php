<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Listener;

use Hyperf\Database\Model\Events\Saving;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

#[Listener]
class TranslationSavingListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            Saving::class,
        ];
    }

    public function process(object $event): void
    {
        $model = $event->getModel();

        // 保存时去除国际化字段
        $tableI18nConfig = config('translation.i18n_table.' . $model->getTable());
        if (! empty($tableI18nConfig)) {
            foreach ($tableI18nConfig['i18n'] as $item) {
                $tmpField = 'i18n_' . $item;
                unset($model->{$tmpField});
            }
        }

        // 保存时去除数字的国际化字段
        $tableI18nConfig = config('translation.number.' . $model->getTable());
        if (! empty($tableI18nConfig)) {
            foreach ($tableI18nConfig as $key => $item) {
                if (is_numeric($key)) {
                    $tmpField = 'i18n_' . $item;
                } else {
                    $tmpField = 'i18n_' . $key;
                }
                unset($model->{$tmpField});
            }
        }

        // 保存时去除金额的国际化字段
        $tableI18nConfig = config('translation.currency.' . $model->getTable());
        if (! empty($tableI18nConfig)) {
            foreach ($tableI18nConfig['field'] as $item) {
                $tmpField = 'i18n_' . $item;
                unset($model->{$tmpField});
            }
        }

        // 保存时去除时间的国际化字段
        $tableI18nConfig = config('translation.datetime.' . $model->getTable());
        if (! empty($tableI18nConfig)) {
            foreach ($tableI18nConfig as $key => $item) {
                if (is_numeric($key)) {
                    $tmpField = 'i18n_' . $item;
                } else {
                    $tmpField = 'i18n_' . $key;
                }
                unset($model->{$tmpField});
            }
        }
    }
}
