<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Helper;

use Bailing\Model\BailingTranslation;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Codec\Json;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class TranslationHelper
{
    /**
     * 创建表.
     */
    #[Cacheable(prefix: 'bailingTranslationTable', ttl: 86400)]
    public static function createTable(): string
    {
        self::createTableCode();
        return 'bailingTranslationTable';
    }

    public static function createTableCode(): bool
    {
        if (! Schema::hasTable('bailing_translation')) {
            Schema::create('bailing_translation', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('org_id')->comment('机构ID')->index('idx_org_id');
                $table->string('table_field', 150)->nullable()->comment('数据表表名和字段名')->index('idx_table_field');
                $table->string('data_id', 50)->nullable()->comment('数据表的关联参数，一般为ID')->index('idx_data_id');
                $table->json('value')->nullable()->comment('多语言的值');
                $table->timestamps();
                $table->comment('国际化内容表');
            });
        }
        return true;
    }

    /**
     * 批量保存多语言内容（不覆盖原有的文本）.
     */
    public static function saveTranslationBatch(int $orgId, string $table, int $dataId, array $value): bool
    {
        foreach ($value as $key => $item) {
            self::saveTranslation($orgId, $table . '_' . $key, $dataId, $item, false);
        }
        return true;
    }

    /**
     * 自动遍历数组保存多语言内容.
     * @param string $table 数据库表名
     * @param int|string $dataId 数据ID
     * @param array $postArr 数据数组（一般为post）
     * @param int $orgId 机构ID（默认为0）
     * @throws \Exception
     */
    public static function autoSaveTranslation(string $table, int|string $dataId, array $postArr, int $orgId = 0): bool
    {
        $tableList = config('translation.i18n_table.' . $table);
        if (empty($tableList)) {
            throw new \Exception('config【translation.i18n_table.' . $table . '】not exists');
        }
        foreach ($tableList['i18n'] as $item) {
            $tmpKey = 'i18n_' . $item;
            if (! empty($postArr[$tmpKey])) {
                self::saveTranslation($orgId, $table . '_' . $item, $dataId, $postArr[$tmpKey]);
            }
        }

        return true;
    }

    /**
     * 保存多语言内容.
     * @param int $orgId 机构ID，全局默认为0
     * @param string $tableField 表名拼接字段名
     * @param int|string $dataId 数据ID
     * @param array $value 多语言内容
     * @param bool $isCover 是否覆盖原有内容
     */
    public static function saveTranslation(int $orgId, string $tableField, int|string $dataId, array $value, bool $isCover = true): bool
    {
        // 没开启国际化，则直接返回成功
        if (! cfg('open_internationalize')) {
            return true;
        }
        $translation = BailingTranslation::query()->firstOrNew(['org_id' => $orgId, 'table_field' => $tableField, 'data_id' => $dataId]);

        if (empty($translation->id) || $isCover) {
            $newValue = $value;
        } else {
            // 不覆盖原有的内容.
            $newValue = $translation->value;
            foreach ($value as $key => $item) {
                if (empty($newValue[$key])) {
                    $newValue[$key] = $item;
                }
            }
        }

        // 自动补全翻译内容.
        if (! empty($newValue['zh_cn'])) {
            $langList = cfg('lang_list');
            foreach ($langList as $key => $item) {
                if (empty($newValue[$key])) {
                    $newValue[$key] = self::translate($newValue['zh_cn'], 'zh-CN', $item);
                }
            }
        }
        $translation->value = $newValue;

        if (! $translation->save()) {
            return false;
        }
        return true;
    }

    /**
     * 谷歌翻译-文本翻译.
     */
    public static function translate(string|array $text, string $source, string $target): string
    {
        if (! cfg('google_translate_key')) {
            return '';
        }
        $sourceReal = match ($source) {
            'zh-HK', 'zh-TW' => 'zh-TW',
            'zh-CN' => 'zh-CN',
            default => $source,
        };
        $targetReal = match ($target) {
            'zh-HK', 'zh-TW' => 'zh-TW',
            'zh-CN' => 'zh-CN',
            default => $target,
        };
        try {
            $result = HttpHelper::formRequest('https://translation.googleapis.com/language/translate/v2', [
                'q' => $text,
                'key' => cfg('google_translate_key'),
                'format' => 'text',
                'model' => 'base',
                'source' => $sourceReal,
                'target' => $targetReal,
            ]);
        } catch (\Exception $e) {
            return '';
        }
        $resultArr = Json::decode($result);
        if (! empty($resultArr['data']['translations'])) {
            if (is_array($text)) {
                return $resultArr['data']['translations'];
            }
            return $resultArr['data']['translations'][0]['translatedText'] ?? '';
        }
        return '';
    }

    /**
     * 转义数组，得到i18n的值.
     * @param array $dataList 数据列表
     * @param string $table 要查询的表名
     * @param array|string $i18nField 要查询的字段（可以传数组）
     * @param string $relationField 关联字段，默认为ID
     * @param int $orgId 机构ID，默认为0
     */
    public static function i18nConvert(array $dataList, string $table, array|string $i18nField, string $relationField = 'id', int $orgId = 0): array
    {
        $isArray = true;

        // 对象形式的，一条数据
        if (empty($dataList[0])) {
            $dataList = [$dataList];
            $isArray = false;
        }

        // 如果关联字段都没有查出来，则直接返回
        if (empty($dataList[0][$relationField])) {
            return $dataList;
        }

        is_string($i18nField) && $i18nField = [$i18nField];

        if (! cfg('open_internationalize')) {
            foreach ($i18nField as $item) {
                // 如果翻译的字段不存在，则不查找
                if (! isset($dataList[0][$item])) {
                    continue;
                }
                foreach ($dataList as $key => $value) {
                    $i18nKey = 'i18n_' . $item;
                    $dataList[$key][$i18nKey] = [
                        'value' => $value[$item],
                        'i18n_value' => [
                            'zh_cn' => $value[$item],
                        ],
                    ];
                }
            }
        } else {
            $i18nFieldValue = arrayColumnUnique($dataList, $relationField);
            foreach ($i18nField as $item) {
                // 如果翻译的字段不存在，则不查找
                if (! isset($dataList[0][$item])) {
                    continue;
                }

                // 查出表里的值
                $i18nValueArr = BailingTranslation::query()
                    ->where(['table_field' => $table . '_' . $item, 'org_id' => $orgId])
                    ->whereIn('data_id', $i18nFieldValue)
                    ->pluck('value', 'data_id');

                // 重新给数组组装i18n的值
                foreach ($dataList as $key => $value) {
                    $i18nKey = 'i18n_' . $item;
                    $dataList[$key][$i18nKey] = [
                        'value' => $value[$item],
                        'i18n_value' => $i18nValueArr[$value[$relationField]] ?? ['zh_cn' => $value[$item]],
                    ];
                }
            }
        }

        if (! $isArray) {
            return $dataList[0];
        }

        return $dataList;
    }
}