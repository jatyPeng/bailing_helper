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

use Bailing\IotCloud\Exception\InvalidArgumentException;
use Bailing\IotCloud\HikCloud\AbstractProvider;

class DeviceProvider extends AbstractProvider
{
    /**
     * 新增设备.
     *
     * @return mixed
     */
    public function addDevice(array $params)
    {
        return $this->postJson('/api/v1/estate/devices', $params);
    }

    /**
     * 修改设备.
     *
     * @return mixed
     */
    public function updateDevice(array $params)
    {
        return $this->postJson('/api/v1/estate/devices/actions/updateDevice', $params);
    }

    /**
     * 删除设备.
     *
     * @return mixed
     */
    public function deleteDevice(string $deviceId)
    {
        return $this->postJson('/api/v1/estate/devices/actions/deleteDevice', ['deviceId' => $deviceId]);
    }

    /**
     * 查询设备详情.
     *
     * @return mixed
     */
    public function getDeviceInfo(string $deviceId)
    {
        return $this->getJson('/api/v1/estate/devices', ['deviceId' => $deviceId]);
    }

    /**
     * 查询社区下的设备列表.
     *
     * @return mixed
     */
    public function getDeviceByCommunityId(array $params)
    {
        return $this->postJson('/api/v1/estate/devices/actions/listByCommunityId', $params);
    }

    /**
     * 查询社区下设备通道列表.
     *
     * @return mixed
     */
    public function getDeviceChannelByCommunityId(array $params)
    {
        return $this->getJson('/api/v1/estate/devices/channels/actions/listByCommunityId', $params);
    }

    /**
     * 设备抓图
     * 该接口仅适用于IPC或者关联IPC的DVR设备，该接口并非预览时的截图功能。海康型号设备可能不支持萤石协议抓拍功能，使用该接口可能返回不支持或者超时。
     * 注意：设备抓图能力有限，请勿频繁调用，频繁调用将会被拉入限制黑名单,建议调用的间隔为4s左右。
     *
     * @return mixed
     */
    public function getCapture(string $channelId)
    {
        $endpoint = "/v1/channels/{$channelId}/capture";
        return $this->postJson($endpoint, ['channelId' => $channelId]);
    }

    /**
     * 设备抓图（支持6000C）
     * 提供设备抓拍当前画面功能。
     * 该接口仅适用于IPC或者关联IPC的DVR设备及6000C下的设备视频通道，该接口并非预览时的截图功能。海康型号设备可能不支持萤石协议抓拍功能，使用该接口可能返回不支持或者超时。
     * 注意：设备抓图能力有限，请勿频繁调用，频繁调用将会被拉入限制黑名单,建议调用的间隔为4s左右。
     *
     * @return mixed
     */
    public function getCaptureNew(string $channelId)
    {
        return $this->postJson('/api/v1/estate/devices/channels/actions/capture', ['channelId' => $channelId]);
    }

    /**
     * 设置设备使能
     * 进行设备参数配置，支持使能和仅测温模式设置(注：不支持6000C).
     *
     * @return mixed
     */
    public function updateDeviceAcsConfig(array $params)
    {
        return $this->postJson('/api/v1/estate/devices/actions/updateDeviceAcsConfig', $params);
    }

    /**
     * ---------------------------------------------------------------------------------------------------------
     * 门禁管理
     * ---------------------------------------------------------------------------------------------------------.
     */

    /**
     * 获取人员门禁设备信息.
     *
     * @return mixed
     */
    public function getRemotePersonList(array $params)
    {
        return $this->getJson('/api/v1/estate/entranceGuard/remoteControl/actions/deviceList', $params);
    }

    /**
     * 人脸信息下发.
     *
     * @return mixed
     */
    public function faceIssued(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/permissions/actions/faceIssued', $params);
    }

    /**
     * 人脸信息删除.
     *
     * @return mixed
     */
    public function deleteFaceIssued(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/permissions/actions/deleteFaceIssued', $params);
    }

    /**
     * 远程控门.
     *
     * @return mixed
     */
    public function gateControl(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/remoteControl/actions/gateControl', $params);
    }

    /**
     * 动态密码
     *
     * @return mixed
     */
    public function dynamicCode(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/permissions/actions/dynamicCode', $params);
    }

    /**
     * 我的二维码
     *
     * @return mixed
     */
    public function getQRcode(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/permissions/actions/getQRcode', $params);
    }

    /**
     * 人员权限下发.
     *
     * @return mixed
     */
    public function authorityIssued(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/permissions/actions/authorityIssued', $params);
    }

    /**
     * 人员权限删除.
     *
     * @return mixed
     */
    public function authorityDelete(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/permissions/actions/authorityDelete', $params);
    }

    /**
     * 呼梯.
     *
     * @return mixed
     */
    public function elevatorControl(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/remoteControl/actions/elevatorControl', $params);
    }

    /**
     * 住户人员权限批量下发.
     *
     * @return mixed
     */
    public function batchAuthorityIssued(array $params)
    {
        return $this->postJson('/api/v1/estate/entranceGuard/permissions/actions/batchAuthorityIssued', $params);
    }

    /**
     * ---------------------------------------------------------------------------------------------------------
     * 视频服务能力
     * ---------------------------------------------------------------------------------------------------------.
     */
    public function ptzControl(array $params)
    {
        if (! \array_key_exists('channelId', $params)) {
            throw new InvalidArgumentException('缺少必要参数 channelId');
        }
        if (! \array_key_exists('direction', $params)) {
            throw new InvalidArgumentException('缺少必要参数 direction');
        }
        if (! \array_key_exists('speed', $params)) {
            throw new InvalidArgumentException('缺少必要参数 speed	');
        }

        $endpoint = "/v1/channels/{$params['channelId']}/ptz/start";
        return $this->post($endpoint, $params);
    }

    public function ptzStop(array $params)
    {
        if (! \array_key_exists('channelId', $params)) {
            throw new InvalidArgumentException('缺少必要参数 channelId');
        }

        $endpoint = "/v1/channels/{$params['channelId']}/ptz/stop";
        return $this->post($endpoint, $params);
    }

    public function addPreset($channelId)
    {
        $endpoint = "/v1/channels/{$channelId}/presets/add";
        return $this->post($endpoint, ['channelId' => $channelId]);
    }

    public function movePreset(array $params)
    {
        if (! \array_key_exists('channelId', $params)) {
            throw new InvalidArgumentException('缺少必要参数 channelId');
        }
        $endpoint = "/v1/channels/{$params['channelId']}/presets/move";
        return $this->post($endpoint, $params);
    }

    public function clearPreset(array $params)
    {
        if (! \array_key_exists('channelId', $params)) {
            throw new InvalidArgumentException('缺少必要参数 channelId');
        }
        $endpoint = "/v1/channels/{$params['channelId']}/presets/clear";
        return $this->post($endpoint, $params);
    }

    /**
     * 关闭设备视频加密.
     *
     * @throws \Imactool\Hikcloud\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function offDeviceVoideEncrypt(array $params)
    {
        if (! \array_key_exists('deviceId', $params)) {
            throw new InvalidArgumentException('缺少必要参数 deviceId');
        }
        $endpoint = "/v1/devices/{$params['deviceId']}/encrypt/off";
        return $this->post($endpoint, $params);
    }

    /**
     * 开通标准流预览功能.
     * @param $channelIds
     *
     * @return mixed
     */
    public function liveVideoOpen($channelIds)
    {
        return $this->post('/v1/devices/liveVideoOpen', ['channelIds' => $channelIds]);
    }

    /**
     * 获取标准流预览地址
     * 该接口获取的标准流预览地址适用于公众号、公共标准流预览等场景，特点是视频信息公开。
     * @param $channelId
     *
     * @return mixed
     */
    public function liveAddress($channelId)
    {
        $endpoint = '/v1/devices/liveAddress?channelId=' . trim($channelId);
        return $this->get($endpoint, ['channelId' => $channelId]);
    }

    public function liveAddressLimit(array $params)
    {
        return $this->post('/v1/devices/liveAddressLimit', $params);
    }

    /**
     * 获取视频取流时需要的认证信息.
     * @return mixed
     */
    public function getEzvizInfo()
    {
        return $this->get('/v1/ezviz/account/info');
    }

    /**
     * （新）获取指定有效期标准流预览地址/回放地址
     * 根据通道ID获取设备通道指定有效期的标准流预览、回放地址信息,支持6000C子设备通道。
     * 该该接口获取的标准流预览地址适用于报警值守、社区监控等场景，特点是视频信息私有，需要有安全性保证。
     *
     * @return mixed
     */
    public function getLiveAddressNew(array $params)
    {
        return $this->postJson('/api/v1/estate/devices/channels/actions/liveAddress', $params);
    }

    /**
     * ---------------------------------------------------------------------------------------------------------
     * 设备确权
     * ---------------------------------------------------------------------------------------------------------.
     */

    /**
     *  设备确权 自动确权.
     *
     * @return mixed
     */
    public function autoconfirm(array $params)
    {
        return $this->getJson('/v1/carrier/wing/endpoint/confirm/right/autoconfirm', $params);
    }

    /**
     * 设备确权
     * 下线确认.
     *
     * @return mixed
     */
    public function offlineconfirm(string $deviceSerial)
    {
        return $this->getJson('/v1/carrier/wing/endpoint/confirm/right/offlineconfirm', ['deviceSerial' => $deviceSerial]);
    }

    /**
     * 设备确权 > 上线确权.
     *
     * @return mixed
     */
    public function onlineconfirm(string $deviceSerial)
    {
        return $this->getJson('/v1/carrier/wing/endpoint/confirm/right/onlineconfirm', ['deviceSerial' => $deviceSerial]);
    }
}
