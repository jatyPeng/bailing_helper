<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\HikCloud\Provider;

use Bailing\IotCloud\HikCloud\AbstractProvider;

class CardProvider extends AbstractProvider
{
    /**
     * 添加一张新的空白卡片。
     *
     * @return mixed
     */
    public function addCard(array $params)
    {
        return $this->postJson('/api/v1/estate/system/cards', $params);
    }

    /**
     * 删除一张空白卡。
     *
     * @return mixed
     */
    public function deleteCard(string $cardId)
    {
        $endpoint = '/api/v1/estate/system/cards/' . $cardId;
        return $this->deleteJson($endpoint, ['cardId' => $cardId]);
    }

    /**
     * 给人员开通卡片。
     *
     * @return mixed
     */
    public function openCard(array $params)
    {
        return $this->postJson('/api/v1/estate/system/cards/actions/openCard', $params);
    }

    /**
     * 退卡
     *
     * @return mixed
     */
    public function refundCard(array $params)
    {
        return $this->postJson('/api/v1/estate/system/cards/actions/refundCard', $params);
    }

    /**
     * 换卡
     *
     * @return mixed
     */
    public function changeCard(array $params)
    {
        return $this->postJson('/api/v1/estate/system/cards/actions/changeCard', $params);
    }

    /**
     * 挂失.
     * @param $cardId
     *
     * @return mixed
     */
    public function lossCard($cardId)
    {
        return $this->postJson('/api/v1/estate/system/cards/actions/lossCard', ['cardId' => $cardId]);
    }

    /**
     * 解挂
     *
     * @return mixed
     */
    public function cancelLossCard(array $params)
    {
        return $this->postJson('/api/v1/estate/system/cards/actions/cancelLossCard', $params);
    }

    /**
     * 补卡
     *
     * @return mixed
     */
    public function reissueCard(array $params)
    {
        return $this->postJson('/api/v1/estate/system/cards/actions/reissueCard', $params);
    }

    /**
     * 查卡
     *
     * @return mixed
     */
    public function getCards(array $params)
    {
        return $this->postJson('/api/v1/estate/system/cards/actions/getCards', $params);
    }
}
