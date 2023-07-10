<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\YunRui\Provider;

use Bailing\IotCloud\YunRui\AbstractProvider;

class MsgProvider extends AbstractProvider
{
    /**
     *  通过消息Id查询消息详情
     * 接口使用前提，必须要在平台-消息业务-报警预案功能里配置预案，不然会查询不到消息详情.
     * @param $messageId
     *
     * @return mixed
     */
    public function getMessageInfoById($messageId)
    {
        $params = ['messageId' => $messageId];
        return $this->postJson('/gateway/messagecenter/api/messageInfo', $params);
    }
}
