<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Constants\Code\Common;

use Bailing\Annotation\EnumCode;
use Bailing\Annotation\EnumCodeInterface;
use Bailing\Annotation\EnumCodePrefix;
use Bailing\Trait\EnumCodeGet;

#[EnumCodePrefix(prefixCode: 100, info: '公共错误')]
enum CommonCode: int implements EnumCodeInterface
{
    use EnumCodeGet;

    #[EnumCode(msg: '请登录', i18nMsg: ['en' => 'Please log in', 'zh_tw' => '請登入', 'zh_hk' => '請登入', 'ja' => 'ログインしてください'])]
    case NEED_LOGIN = 1;

    #[EnumCode(msg: '登录状态已过期，请重新登录！', i18nMsg: ['en' => 'Login status has expired, please log in again!', 'zh_tw' => '登入狀態已過期，請重新登入！', 'zh_hk' => '登入狀態已過期，請重新登入！', 'ja' => 'ログイン状態が期限切れになりました。再ログインしてください！'])]
    case LOGIN_EXPIRED = 2;

    #[EnumCode(msg: '参数错误', i18nMsg: ['en' => 'Parameter error', 'zh_tw' => '參數錯誤', 'zh_hk' => '參數錯誤', 'ja' => 'パラメータエラー'])]
    case PARAM_ERROR = 3;

    #[EnumCode(msg: '用户信息不存在,请重新注册登录！', i18nMsg: ['en' => 'User information does not exist, please register and log in again!', 'zh_tw' => '用戶信息不存在,請重新註冊登入！', 'zh_hk' => '用戶信息不存在,請重新註冊登入！', 'ja' => 'ユーザー情報が存在しません。再登録してログインしてください！'])]
    case USER_NOT_EXITS = 4;

    #[EnumCode(msg: '您已被移出该机构!', i18nMsg: ['en' => 'You have been removed from the organization!', 'zh_tw' => '您已被移出該機構!', 'zh_hk' => '您已被移出該機構!', 'ja' => 'あなたは組織から外されました！'])]
    case USER_NOT_IN_ORG = 5;

    #[EnumCode(msg: '需要内网才能访问该接口（当前IP：{ip}）', i18nMsg: ['en' => 'Need intranet to access this interface (current IP: {ip})', 'zh_tw' => '需要內網才能訪問該接口（當前IP：{ip}）', 'zh_hk' => '需要內網才能訪問該接口（當前IP：{ip}）', 'ja' => 'インタフェースにアクセスするにはイントラネットが必要です（現在のIP:{ip}）'])]
    case VISIT_NEED_INTRANET = 6;

    #[EnumCode(msg: '账号异常!未绑定角色身份', i18nMsg: ['en' => 'Account abnormal! Not bound to role identity', 'zh_tw' => '帳號異常!未綁定角色身份', 'zh_hk' => '帳號異常!未綁定角色身份', 'ja' => 'アカウント異常!役割のアイデンティティがバインドされていません'])]
    case NOT_BIND_ROLE = 7;

    #[EnumCode(msg: '无权访问', i18nMsg: ['en' => 'No authority to access', 'zh_tw' => '無權訪問', 'zh_hk' => '無權訪問', 'ja' => '権限がありません'])]
    case AUTH_ERROR = 8;

    #[EnumCode(msg: '无权访问（{action}）', i18nMsg: ['en' => 'No authority to access ({action})', 'zh_tw' => '無權訪問（{action}）', 'zh_hk' => '無權訪問（{action}）', 'ja' => '権限がありません（{action}）'])]
    case AUTH_ERROR_ACTION = 9;

    #[EnumCode(msg: '保存失败，请重试', i18nMsg: ['en' => 'Failed to save, please try again', 'zh_tw' => '儲存失敗，請重試', 'zh_hk' => '儲存失敗，請重試', 'ja' => '保存に失敗しました。再試行してください'])]
    case SAVE_FAILED = 10;

    #[EnumCode(msg: '保存成功', i18nMsg: ['en' => 'Saved successfully', 'zh_tw' => '儲存成功', 'zh_hk' => '儲存成功', 'ja' => '保存に成功しました'])]
    case SAVE_SUCCESS = 11;

    #[EnumCode(msg: '操作成功', i18nMsg: ['en' => 'Operation successful', 'zh_tw' => '操作成功', 'zh_hk' => '操作成功', 'ja' => '操作に成功しました'])]
    case OPERATION_SUCCESS = 12;

    #[EnumCode(msg: '操作失败，请重试', i18nMsg: ['en' => 'Operation failed, please try again', 'zh_tw' => '操作失敗，請重試', 'zh_hk' => '操作失敗，請重試', 'ja' => '操作に失敗しました。再試行してください'])]
    case OPERATION_FAILED = 13;

    #[EnumCode(msg: '退出成功', i18nMsg: ['en' => 'Logout successful', 'zh_tw' => '退出成功，請重試', 'zh_hk' => '退出成功，請重試', 'ja' => 'ログアウトに成功しました'])]
    case LOGOUT_SUCCESS = 14;

    #[EnumCode(msg: '退出失败，请重试', i18nMsg: ['en' => 'Logout failed, please try again', 'zh_tw' => '退出失敗，請重試', 'zh_hk' => '退出失敗，請重試', 'ja' => 'ログアウトに失敗しました。再試行してください'])]
    case LOGOUT_FAILED = 15;

    #[EnumCode(msg: '账号异常，请重新登录', i18nMsg: ['en' => 'Account abnormal, please log in again', 'zh_tw' => '帳號異常，請重新登入', 'zh_hk' => '帳號異常，請重新登入', 'ja' => 'アカウント異常です。再ログインしてください'])]
    case ACCOUNT_ABNORMAL = 16;

    #[EnumCode(msg: '导入成功', i18nMsg: ['en' => 'Import successful', 'zh_tw' => '導入成功', 'zh_hk' => '導入成功', 'ja' => 'インポートに成功しました'])]
    case IMPORT_SUCCESS = 17;

    #[EnumCode(msg: '导入失败，请重试', i18nMsg: ['en' => 'Import failed, please try again', 'zh_tw' => '導入失敗，請重試', 'zh_hk' => '導入失敗，請重試', 'ja' => 'インポートに失敗しました。再試行してください'])]
    case IMPORT_FAILED = 18;

    #[EnumCode(msg: '导出成功', i18nMsg: ['en' => 'Export successful', 'zh_tw' => '導出成功', 'zh_hk' => '導出成功', 'ja' => 'エクスポートに成功しました'])]
    case EXPORT_SUCCESS = 19;

    #[EnumCode(msg: '导出失败，请重试', i18nMsg: ['en' => 'Export failed, please try again', 'zh_tw' => '導出失敗，請重試', 'zh_hk' => '導出失敗，請重試', 'ja' => 'エクスポートに失敗しました。再試行してください'])]
    case EXPORT_FAILED = 20;
}
