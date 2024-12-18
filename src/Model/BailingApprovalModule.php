<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */

namespace Bailing\Model;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $name 审批模板名称
 * @property string $icon 审批模板图标地址
 * @property string $alias 审批的别名字符串
 * @property int $approval_type 0审批1是租客审批应用
 * @property string $form 表单
 * @property string $source_type 添加模板的应用/模块名称别名
 * @property string $source_txt 添加模板的应用/模块名称
 * @property string $cat_type 分类名称
 * @property string $sub_cat_type 子分类名称
 * @property string $sub_cat_alias 子分类别名
 * @property int $start_user_type
 * @property string $created_name 创建数据的人员名字
 * @property string $updated_name 最后修改数据的人员名字
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 */
class BailingApprovalModule extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'bailing_approval_module';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'approval_type' => 'integer', 'start_user_type' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'form' => 'array'];
}
