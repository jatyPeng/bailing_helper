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

class MixProvider extends AbstractProvider
{
    protected $mixHeader;

    /**
     * 社区-混合云三方接口 公共请求头.
     * @param mixed $communityCode
     */
    public function mixHeader($communityCode)
    {
        $this->mixHeader = ['communityCode' => $communityCode];
        return $this;
    }

    /**
     * 查询开门记录.
     *
     * @return mixed
     */
    public function doorOpenRecord(array $params)
    {
        return $this->postJson('/gateway/dsc-messagecenter/v1.1/card/record', $params, $this->mixHeader);
    }

    /**
     * 业主二维码开门.
     *
     * @return mixed
     */
    public function ownerQrOpenDoor(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body', $params, $this->mixHeader);
    }

    /**
     * 删除人员.
     *
     * @return mixed
     */
    public function deletePerson(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/4', $params, $this->mixHeader);
    }

    /**
     * 删除车辆.
     *
     * @return mixed
     */
    public function deleteCar(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/8', $params, $this->mixHeader);
    }

    /**
     * 开通卡片.
     *
     * @return mixed
     */
    public function openCard(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/8', $params, $this->mixHeader);
    }

    /**
     * 挂失卡片.
     *
     * @return mixed
     */
    public function loseCard(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/17', $params, $this->mixHeader);
    }

    /**
     * 控制停车场道闸.
     *
     * @return mixed
     */
    public function controlParkingLot(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/15', $params, $this->mixHeader);
    }

    /**
     * 收费记录查询.
     *
     * @return mixed
     */
    public function billRecord(array $params)
    {
        return $this->postJson('/gateway/dsc-community/cs/ipms/consume/record/page', $params, $this->mixHeader);
    }

    /**
     * 新增APP人员.
     *
     * @return mixed
     */
    public function addPersonForApp(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/9', $params, $this->mixHeader);
    }

    /**
     * 新增人员.
     *
     * @return mixed
     */
    public function addPerson(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/3', $params, $this->mixHeader);
    }

    /**
     * 新增车辆.
     *
     * @return mixed
     */
    public function addCar(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/7', $params, $this->mixHeader);
    }

    /**
     * 更新人员.
     *
     * @return mixed
     */
    public function updatePerson(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/5', $params, $this->mixHeader);
    }

    /**
     * 更新人员图片.
     *
     * @return mixed
     */
    public function updatePersonImg(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/6', $params, $this->mixHeader);
    }

    /**
     * 查询人员信息.
     *
     * @return mixed
     */
    public function searchPerson(array $params)
    {
        return $this->postJson('/gateway/dsc-community/cs/person/page', $params, $this->mixHeader);
    }

    /**
     * 查询停车场厂区.
     *
     * @return mixed
     */
    public function searchCarArea(array $params)
    {
        return $this->postJson('/gateway/dsc-community/cs/ipms/queryParkingLot/page', $params, $this->mixHeader);
    }

    /**
     * 查询应缴费用.
     *
     * @return mixed
     */
    public function searchBil(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/12', $params, $this->mixHeader);
    }

    /**
     * 人员更新.
     *
     * @return mixed
     */
    public function personUpdate(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/10', $params, $this->mixHeader);
    }

    /**
     *  查询授权接口.
     * @return mixed
     */
    public function searchAuth()
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/21', [], $this->mixHeader);
    }

    /**
     * 查询设备.
     *
     * @return mixed
     */
    public function searchDevice(array $params)
    {
        return $this->postJson('/gateway/dsc-community/cs/device', $params, $this->mixHeader);
    }

    /**
     * 查询访客刷卡记录.
     *
     * @return mixed
     */
    public function getVisitorRecord(array $params)
    {
        return $this->postJson('/gateway/dsc-messagecenter/v1.1/card/record/visitor', $params, $this->mixHeader);
    }

    /**
     * 查询过车记录.
     *
     * @return mixed
     */
    public function getCarRecord(array $params)
    {
        return $this->postJson('/gateway/dsc-community/cs/ipms/carCapture/page', $params, $this->mixHeader);
    }

    /**
     * 根据呼叫号码查询通道信息.
     *
     * @return mixed
     */
    public function getChannelInfoByPhone(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/29', $params, $this->mixHeader);
    }

    /**
     *  用户缴费.
     *
     * @return mixed
     */
    public function userBill(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/14', $params, $this->mixHeader);
    }

    /**
     *  缴费成功通知.
     *
     * @return mixed
     */
    public function billNotice(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/13', $params, $this->mixHeader);
    }

    /**
     *  门禁/门口机远程开门.
     *
     * @return mixed
     */
    public function remoteOpenDoor(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/2', $params, $this->mixHeader);
    }

    /**
     *  获取CSC地址
     *
     * @return mixed
     */
    public function getCsc(array $params)
    {
        $uri = "/gateway/cmms/v1/service/csc/{$params['telephone']}/{$params['tags']}";
        return $this->postJson($uri, $params, $this->mixHeader);
    }

    /**
     *  获取P2P服务基本信息.
     *
     * @return mixed
     */
    public function getP2pInfo(array $params)
    {
        return $this->postJson('/gateway/cmms/v1.1/service/p2p/server', $params, $this->mixHeader);
    }

    /**
     *  获取物业公司编码
     *
     * @return mixed
     */
    public function getCompanyAppInfo(array $params)
    {
        return $this->postJson('/gateway/membership/apply/companyAppInfo', $params, $this->mixHeader);
    }

    /**
     *  获取视频rtsp地址
     *
     * @return mixed
     */
    public function getRtsp(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1/video/consult', $params, $this->mixHeader);
    }

    /**
     *  补卡
     *
     * @return mixed
     */
    public function upCard(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/20', $params, $this->mixHeader);
    }

    /**
     *  解除挂失.
     *
     * @return mixed
     */
    public function cancelCard(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/19', $params, $this->mixHeader);
    }

    /**
     *  退卡
     *
     * @return mixed
     */
    public function returnCard(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/18', $params, $this->mixHeader);
    }

    /**
     *  邀请下级访客.
     *
     * @return mixed
     */
    public function inviteVisitor(array $params)
    {
        return $this->postJson('/gateway/dsc-mas/v1.1/third/down/body/11', $params, $this->mixHeader);
    }
}
