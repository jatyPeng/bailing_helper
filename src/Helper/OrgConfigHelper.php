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

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\DbConnection\Db;

class OrgConfigHelper
{
    /**
     * 创建表.
     */
    #[Cacheable(prefix: 'bailingOrgConfigTable', ttl: 86400)]
    public static function createTable(): string
    {
        $queryList = Db::select("SHOW TABLES LIKE 'bailing_org_config'");
        if (empty($queryList)) {
            Db::select("CREATE TABLE `bailing_org_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `org_id` int DEFAULT NULL COMMENT '机构ID',
  `index` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '额外的唯一索引名，例如（项目ID_楼宇ID）',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '值',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_org_id` (`org_id`),
  KEY `idx_index` (`index`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
        return 'bailingOrgConfigTable';
    }

    /**
     * 读取配置值（缓存10分钟）.
     * @param int $orgId 机构ID
     * @param string $name 配置名
     * @param string $index 唯一索引值，用于细项配置（例如 项目ID_楼宇ID，店铺ID）
     * @return string
     */
    #[Cacheable(prefix: 'bailingOrgConfig', value: '_#{orgId}_#{name}_#{index}', ttl: 600)]
    public static function getConfig(int $orgId, string $name, string $index = ''): string
    {
        //自动创建表，通过缓存来判断有没有创建过.
        if (! env('BAILING_ORG_CONFIG_BUILDED')) {
            self::createTable();
        }

        $where = [
            'org_id' => $orgId,
            'name' => $name,
        ];
        ! empty($index) && $where['index'] = $index;
        $configValue = Db::table('bailing_org_config')->where($where)->value('value');
        return (string) $configValue;
    }

    /**
     * 写缓存.因为每次应该先读出来渲染页面，然后保存再写。所以不创建表。
     * @param int $orgId 机构ID
     * @param string $name 配置名称
     * @param string $value 配置值
     * @param string $index 唯一索引值，用于细项配置（例如 项目ID_楼宇ID，店铺ID）
     */
    #[CachePut(prefix: 'bailingOrgConfig', value: '_#{orgId}_#{name}_#{index}', ttl: 600)]
    public static function setConfig(int $orgId, string $name, string $value, string $index = ''): string
    {
        $where = [
            'org_id' => $orgId,
            'name' => $name,
        ];
        ! empty($index) && $where['index'] = $index;
        $configArr = Db::table('bailing_org_config')->where($where)->first();
        if (! $configArr) {
            Db::table('bailing_org_config')->insert([
                'org_id' => $orgId,
                'name' => $name,
                'value' => $value,
                'index' => $index,
                'created_at' => getTime(),
                'updated_at' => getTime(),
            ]);
        } else {
            Db::table('bailing_org_config')->where($where)->update([
                'value' => $value,
                'updated_at' => getTime(),
            ]);
        }
        return $value;
    }

    /**
     * 清除配置值（缓存10分钟）.
     * @param int $orgId 机构ID
     * @param string $name 配置名
     * @param string $index 唯一索引值，用于细项配置（例如 项目ID_楼宇ID，店铺ID）
     */
    #[CacheEvict(prefix: 'bailingOrgConfig', value: '_#{orgId}_#{name}_#{index}')]
    public static function clearCache(int $orgId, string $name, string $index = ''): void
    {
    }
}
