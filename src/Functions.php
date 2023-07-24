<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
use Bailing\Helper\ConfigHelper;
use Bailing\Helper\LinkLibraryHelper;
use Bailing\Helper\RequestHelper;
use Hyperf\Codec\Json;
use Hyperf\Context\ApplicationContext;
use Hyperf\Context\Context;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\RedisFactory;
use Hyperf\Server\ServerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

//定义缓存存放的主目录
if (! defined('RUNTIME_BASE_PATH') && defined('BASE_PATH')) {
    define('RUNTIME_BASE_PATH', str_starts_with(BASE_PATH, 'phar://') ? '/tmp/bailing/' . env('APP_NAME') : BASE_PATH . '/runtime');
}

if (! function_exists('container')) {
    /**
     * 容器实例.
     * @return ContainerInterface
     */
    function container()
    {
        return ApplicationContext::getContainer();
    }
}

if (! function_exists('service')) {
    /**
     * 增加微服务快速调用的方法.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function service(mixed $class): mixed
    {
        return ApplicationContext::getContainer()->get($class);
    }
}

if (! function_exists('redis')) {
    /**
     * redis 客户端实例.
     * @param mixed $poolName
     * @return \Hyperf\Redis\Redis|mixed
     */
    function redis($poolName = '')
    {
        if ($poolName) {
            return container()->get(RedisFactory::class)->get($poolName);
        }
        return container()->get(Hyperf\Redis\Redis::class);
    }
}

if (! function_exists('server')) {
    /**
     * server 实例 基于 swoole server.
     * @return \Swoole\Coroutine\Server|\Swoole\Server
     */
    function server()
    {
        return container()->get(ServerFactory::class)->getServer()->getServer();
    }
}

if (! function_exists('cache')) {
    /**
     * 缓存实例 简单的缓存.
     * @return mixed|\Psr\SimpleCache\CacheInterface
     */
    function cache()
    {
        return container()->get(Psr\SimpleCache\CacheInterface::class);
    }
}

if (! function_exists('stdLog')) {
    /**
     * 向控制台输出日志.
     * @return mixed|StdoutLoggerInterface
     */
    function stdLog()
    {
        return container()->get(StdoutLoggerInterface::class);
    }
}

if (! function_exists('logger')) {
    /**
     * 向日志文件记录日志.
     * @return \Psr\Log\LoggerInterface
     */
    function logger()
    {
        return container()->get(LoggerFactory::class)->make();
    }
}

if (! function_exists('request')) {
    /**
     * 请求对象
     * @param null|mixed $class
     * @return mixed|RequestInterface
     */
    function request($class = null)
    {
        if (! $class) {
            $class = RequestInterface::class;
        }
        return container()->get($class);
    }
}

if (! function_exists('domain')) {
    /**
     * 获取数据库中的配置值.
     * @param string $name 配置项的名称
     * @return mixed|string
     */
    function domain(): string
    {
        return RequestHelper::getClientScheme() . '://' . RequestHelper::getClientDomain();
    }
}

if (! function_exists('cfg')) {
    /**
     * 获取数据库中的配置值.
     * @param string $name 配置项的名称
     * @return mixed|string
     */
    function cfg(string $name): ?string
    {
        return ConfigHelper::getConfig($name);
    }
}

if (! function_exists('link')) {
    /**
     * 获取数据库中的链接库.
     * @param string $alias 链接的别名
     * @param string $port 链接的端
     */
    function link(string $alias, string $port = 'user'): array
    {
        return LinkLibraryHelper::getLink($alias, $port);
    }
}

if (! function_exists('contextSet')) {
    /**
     * 储存一个值到当前协程的上下文.
     * @param string $name key
     * @param mixed $value value
     */
    function contextSet(string $name, $value): mixed
    {
        return Context::set($name, $value);
    }
}

if (! function_exists('contextGet')) {
    /**
     * 从当前协程的上下文中取出一个以 $id 为 key 储存的值.
     * @param string $name key
     */
    function contextGet(string $name): mixed
    {
        return Context::get($name);
    }
}

if (! function_exists('formatTime')) {
    /**
     * 格式化展示时间，方便全球不同的展示形式，后续再处理其他国家的.
     * @param ?string $unixTime 时间
     */
    function formatTime(?string $unixTime): ?string
    {
        if (is_null($unixTime)) {
            return null;
        }
        return $unixTime;
    }
}

if (! function_exists('fileDomain')) {
    /**
     * 补全cos文件的访问路径.
     * @param null|string $filePath 文件路径
     */
    function fileDomain(?string $filePath): string
    {
        if (empty($filePath)) {
            return '';
        }

        if (str_starts_with($filePath, 'http')) {
            return $filePath;
        }

        $domain = '';
        if (cfg('filesystem_type') == 'cos' || cfg('filesystem_type') == '') {
            if (cfg('filesystem_qcloud_domain')) {
                $domain = 'https://' . cfg('filesystem_qcloud_domain') . '/';
            } else {
                $domain = 'https://' . cfg('filesystem_qcloud_bucket') . '-' . cfg('qcloud_appid') . '.cos.' . cfg('filesystem_qcloud_region') . '.myqcloud.com/';
            }
        } elseif (cfg('filesystem_type') == 'minio' || cfg('filesystem_type') == 's3') {
            $domain = trim(cfg('filesystem_s3_endpoint'), '/') . '/' . cfg('filesystem_s3_bucket') . '/';
        } elseif (cfg('filesystem_type') == 'oss') {
            $domain = 'https://' . cfg('filesystem_oss_domain') . '/';
        } elseif (cfg('filesystem_type') == 'ftp') {
            $domain = trim(cfg('filesystem_ftp_domain'), '/') . '/';
        }

        return $domain . $filePath;
    }
}

if (! function_exists('thumbImg')) {
    /**
     * 图片展现时裁剪的功能.
     * @param string $img 图片地址
     * @param int $width 缩小后宽度（移动端全屏一般传递375）
     * @param int $height 缩小后高度
     * @param string $resize 缩小方式（fill固定宽高居中裁剪，其他值强制宽高居中压缩）
     * @param int $scale 缩放比例（如果传2，缩小图片会自动乘以2）
     */
    function thumbImg(string $img, int $width, int $height = 0, string $resize = '', int $scale = 2): string
    {
        if (empty($img)) {
            return '';
        }

        if (! str_starts_with($img, 'http')) {
            return $img;
        }

        $newWidth = $width * $scale;
        $newHeight = $height * $scale;

        if (cfg('filesystem_type') == 'cos' || cfg('filesystem_type') == '') {
            //如果是 fill
            if ($resize == 'fill') {
                return $img . '?imageView2/1/w/' . $newWidth . '/h/' . $newHeight;
            }
            return $img . '?imageView2/2/w/' . $newWidth . '/h/' . $newHeight;
        }
        if (cfg('filesystem_type') == 'minio' || cfg('filesystem_type') == 's3') {
            //暂时无法压缩，需要后续再调研
            return $img . '?from=s3';
        }
        if (cfg('filesystem_type') == 'oss') {
            //如果是 fill
            if ($resize == 'fill') {
                return $img . '?x-oss-process=image/resize,m_fill,w_' . $newWidth . ',h_' . $newHeight;
            }
            return $img . '?x-oss-process=image/resize,w_' . $newWidth . ',h_' . $newHeight;
        }
        if (cfg('filesystem_type') == 'ftp') {
            //暂时无法压缩，需要后续再调研
            return $img . '?from=ftp';
        }
        return $img;
    }
}

if (! function_exists('trimArr')) {
    /**
     * 去除数组里的字符串类型的特定下标字段的空格.
     * @param array $stringArr 字符串数组
     * @param array $filterArr 要去除的下标字段，不传为所有字符串类型都去除
     */
    function trimArr(array $stringArr, array $filterArr = []): array
    {
        // 数据为空
        if (empty($filterArr)) {
            foreach ($stringArr as &$item) {
                is_string($item) && $item = trim($item);
            }
            return $stringArr;
        }

        // 循环下标字段
        foreach ($filterArr as &$filterItem) {
            if (isset($stringArr[$filterItem]) && is_string($stringArr[$filterItem])) {
                $stringArr[$filterItem] = trim($stringArr[$filterItem]);
            }
        }
        return $stringArr;
    }
}

if (! function_exists('getMillisecond')) {
    /**
     * 获取当前毫秒.
     */
    function getMillisecond(): float
    {
        [$msec, $sec] = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}

if (! function_exists('relativePath')) {
    /**
     * Notes: 转换业务前端表单提交的图片路径 公共方法业务表统一化存储相对路径  //fileDomain 补全文件路径
     * User: Endness
     * Date: 2021/10/26
     * Time: 10:10.
     * @param array|string $filePath 文件路径
     */
    function relativePath(string|array $filePath): array|string
    {
        $domainSec = [];
        if (cfg('filesystem_type') == 'cos' || cfg('filesystem_type') == '') {
            $domainSec = [
                'https://' . cfg('filesystem_qcloud_bucket') . '-' . cfg('qcloud_appid') . '.cos.' . cfg('filesystem_qcloud_region') . '.myqcloud.com',
            ];
            if (cfg('filesystem_qcloud_domain')) {
                $domainSec[] = 'https://' . cfg('filesystem_qcloud_domain');
            }
        } elseif (cfg('filesystem_type') == 'minio' || cfg('filesystem_type') == 's3') {
            $domainSec = [
                trim(cfg('filesystem_s3_endpoint'), '/') . '/' . cfg('filesystem_s3_bucket'),
            ];
        } elseif (cfg('filesystem_type') == 'oss') {
            $domainSec = [
                'https://' . cfg('filesystem_oss_domain'),
            ];
        } elseif (cfg('filesystem_type') == 'ftp') {
            $domainSec = [
                trim(cfg('filesystem_ftp_domain'), '/'),
            ];
        }

        if (is_string($filePath)) {
            return ltrim(str_replace($domainSec, '', $filePath), '/');
        }

        // count(xxx) = count(xxx, 1); 1是计算多维数组中的所有元素，避免传过来的是多维数组。
        if (is_array($filePath) && count($filePath) == count($filePath, 1)) {
            foreach ($filePath as $k => $path) {
                $filePath[$k] = ltrim(str_replace($domainSec, '', $path), '/');
            }
            return $filePath;
        }
        return $filePath;
    }
}

if (! function_exists('getFormatNumber')) {
    /**
     * 获取格式化的数字格式.
     */
    function getFormatNumber(string|int|float|null $number): string
    {
        $number = number_format(floatval($number), 2);
        if (str_contains($number, '.')) {
            $number = rtrim($number, '0');
            $number = rtrim($number, '0');
            $number = rtrim($number, '.');
        }
        $number = str_replace(',', '', $number);

        if ($number === '') {
            $number = '0';
        }

        return $number;
    }
}

if (! function_exists('gzEncodeData')) {
    /**
     * Notes: 压缩长文本数据
     * User: Endness
     * Date: 2021/12/7
     * Time: 11:15.
     * @param $data
     */
    function gzEncodeData(string $data): string
    {
        return base64_encode(gzdeflate($data, 9));
    }
}

if (! function_exists('gzDecodeData')) {
    /**
     * Notes: 解压长文本数据
     * User: Endness
     * Date: 2021/12/7
     * Time: 11:54.
     */
    function gzDecodeData(string $data): string
    {
        return gzinflate(base64_decode($data));
    }
}

if (! function_exists('getTime')) {
    /**
     * 获取能直接存入数据库 datetime 格式的时间.
     * @param int $plusSecond 当前时间增加的秒数，减传负数
     */
    function getTime(int $plusSecond = 0): string
    {
        return date('Y-m-d H:i:s', time() + $plusSecond);
    }
}
if (! function_exists('arrayColumnUnique')) {
    /**
     * 获取数组列的值，且唯一，且去除指定的值。一般用于读取数组里的列值后再in查询使用.
     * @param array $array 数组
     * @param string $columnKey 列的键
     * @param array $diffArr 去除掉的值
     */
    function arrayColumnUnique(array $array, string $columnKey, array $diffArr = [0, '0', null]): array
    {
        return array_diff(array_unique(array_column($array, $columnKey)), $diffArr);
    }
}

if (! function_exists('validateForm')) {
    /**
     * 校验form，且返回trim后的数组.
     * @param $request
     * @param null $modelClass
     */
    function validateForm($request, string $scene = '', $modelClass = null): array
    {
        $newRequest = request($request);
        if ($scene) {
            $newRequest->scene($scene)->validateResolved();
        } else {
            $newRequest->validated();
        }
        if ($modelClass) {
            return trimArr($newRequest->all(), $modelClass::trimFields());
        }
        return $newRequest->all();
    }
}

if (! function_exists('getCheckedBuild')) {
    /**
     * 根据 header 获取选中的楼宇信息.
     */
    function getCheckedBuild(null|array $checkedBuildArr = []): array
    {
        if (! $checkedBuildArr) {
            $checkedBuild = request()->getHeaderLine('checked-build');
            if ($checkedBuild) {
                $checkedBuildArr = Json::decode($checkedBuild);
            }
        }

        $returnData = [
            'villageIdArr' => [],
            'buildIdArr' => [],
            'buildArr' => [],
            'villageType' => '',
        ];
        if ($checkedBuildArr) {
            foreach ($checkedBuildArr as $item) {
                $returnData['villageIdArr'][] = $item['id'];
                $item['build'] && $returnData['buildIdArr'] = array_merge($returnData['buildIdArr'], $item['build']);
                $returnData['buildArr'][$item['id']] = $item['build'];
            }
            isset($checkedBuildArr[0]['type']) && $returnData['villageType'] = $checkedBuildArr[0]['type'];
        }

        return $returnData;
    }
}

if (! function_exists('arrAddKey')) {
    /**
     * 给数组加个key，便于移动端 数组 渲染.
     */
    function arrAddKey(?array $data, string $prefix, string $useKey): array
    {
        if (empty($data) || ! is_array($data) || ! isset($data[0])) {
            return $data;
        }

        foreach ($data as &$value) {
            // 二维需要是数组
            if (is_array($value) && ! isset($value[0])) {
                $value['arrKey'] = $prefix . $value[$useKey];
            }
        }

        return $data;
    }
}

if (! function_exists('formatDate')) {
    /**
     * 格式化日期.
     * @param mixed $format
     */
    function formatDate(?string $dateTime, ?string $format = 'm-d H:i'): string
    {
        if (! $dateTime) {
            return $dateTime;
        }

        $unixTime = strtotime($dateTime);
        //特殊格式，年相同则隐藏
        if ($format == 'm-d H:i') {
            $year = date('Y', $unixTime);
            if ($year != date('Y')) {
                return date('Y-' . $format, $unixTime);
            }
        }
        return date($format, $unixTime);
    }
}

if (! function_exists('formatWeekDay')) {
    /**
     * 格式化周几.
     */
    function formatWeekDay(int $weekdayNum, string $format = 'zh-cn'): string
    {
        if ($format == 'zh-cn') {
            return match ($weekdayNum) {
                0 => '日',
                1 => '一',
                2 => '二',
                3 => '三',
                4 => '四',
                5 => '五',
                6 => '六',
            };
        }
        if ($format == 'en') {
            return match ($weekdayNum) {
                0 => 'Sunday',
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
            };
        }
        return '';
    }
}

if (! function_exists('arraySort')) {
    /**
     * 二维数组数组排序.
     */
    function arraySort(array $arr, mixed $key, string $type = 'asc'): array
    {
        $keyArr = array_column($arr, $key);
        if ($type == 'asc') {
            array_multisort($keyArr, SORT_ASC, $arr);
        } else {
            array_multisort($keyArr, SORT_DESC, $arr);
        }
        return $arr;
    }
}

if (! function_exists('getMillisecond')) {
    /**
     * 获取毫秒时间.
     */
    function getMillisecond(): int
    {
        [$s1, $s2] = explode(' ', microtime());
        return (int) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}

if (! function_exists('buildOrderId')) {
    /**
     * 生成订单号.
     * strLen 最小建议18位.最大必须28位.
     */
    function buildOrderId(int $user_id = 0, int $strLen = 28): string
    {
        if ($user_id == 0) {
            $user_id = mt_rand(1000000000, 9999999999);
        }
        if ($strLen == 28) {
            return date('YmdHis') . str_pad(strval($user_id), 12, '0', STR_PAD_LEFT) . mt_rand(10, 99);
        }
        //小于28位
        return date('YmdHis') . substr(str_pad(strval($user_id), 12, '0', STR_PAD_LEFT), ($strLen - 16) * -1) . mt_rand(10, 99);
    }
}

if (! function_exists('tmpDir')) {
    /**
     * 临时目录.
     */
    function tmpDir(): string
    {
        $dir = '/tmp/bailing/' . env('APP_NAME', 'helper');
        ! file_exists($dir) && mkdir($dir, 0777, true);
        return $dir . '/';
    }
}

if (! function_exists('getDistance')) {
    /**
     * 获取两个经纬度的距离.
     * @param mixed $lat1
     * @param mixed $lon1
     * @param mixed $lat2
     * @param mixed $lon2
     */
    function getDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371393; //地球平均半径,单位米
        $dLat = deg2rad($lat2 - $lat1); //角度转化为弧度
        $dLon = deg2rad($lon2 - $lon1);
        $a = pow(sin($dLat / 2), 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * pow(sin($dLon / 2), 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        return round($d);
    }
}

if (! function_exists('secsToStr')) {
    /**
     * 秒转为天数.
     */
    function secsToStr(int $secs, bool $withSec = true, string $format = 'zh-cn'): string
    {
        $r = '';
        if ($secs >= 86400) {
            $days = floor($secs / 86400);
            $secs = $secs % 86400;
            if ($format == 'zh-cn') {
                $r = $days . '天';
            } else {
                $r = $days . ' day';
                if ($days != 1) {
                    $r .= 's';
                }
                if ($secs > 0) {
                    $r .= ', ';
                }
            }
        }

        if ($secs >= 3600) {
            $hours = floor($secs / 3600);
            $secs = $secs % 3600;

            if ($format == 'zh-cn') {
                $r .= $hours . '小时';
            } else {
                $r .= $hours . ' hour';
                if ($hours != 1) {
                    $r .= 's';
                }
                if ($secs > 0) {
                    $r .= ', ';
                }
            }
        }

        if ($secs >= 60) {
            $minutes = floor($secs / 60);
            $secs = $secs % 60;
            if ($format == 'zh-cn') {
                $r .= $minutes . '分';
            } else {
                $r .= $minutes . ' minute';
                if ($minutes != 1) {
                    $r .= 's';
                }
                if ($secs > 0) {
                    $r .= ', ';
                }
            }
        }

        if ($withSec && $secs > 0) {
            if ($format == 'zh-cn') {
                $r .= $secs . '秒';
            } else {
                $r .= $secs . ' second';
                if ($secs != 1) {
                    $r .= 's';
                }
            }
        }

        return $r;
    }
}

if (! function_exists('strDiffNum')) {
    /**
     * 字符串中不同的字数数量.
     */
    function strDiffNum(string $str1, string $str2): int
    {
        $str1Arr = mb_str_split($str1);
        $str2Arr = mb_str_split($str2);
        $maxLength = max(count($str1Arr), count($str2Arr));

        $diffNum = 0;
        for ($i = 0; $i < $maxLength; ++$i) {
            if (! isset($str1Arr[$i]) || ! isset($str2Arr[$i]) || $str1Arr[$i] != $str2Arr[$i]) {
                ++$diffNum;
            }
        }
        return $diffNum;
    }
}

if (! function_exists('genListData')) {
    /**
     * 生成标准的后端返回数组，sql 通过 paginate 查出来的数据.
     */
    function genListData(array $list, array $extraArr = []): array
    {
        $returnArr = ['list' => $list['data'], 'total_page' => $list['last_page'], 'total' => $list['total'], 'current_page' => $list['current_page']];
        return array_merge($returnArr, $extraArr);
    }
}

if (! function_exists('getTopHost')) {
    /**
     * 获取顶级域名.
     */
    function getTopHost(string $url): string
    {
        $hosts = parse_url(strtolower($url));
        $host = $hosts['host'] ?? $hosts['path'];   //如果只是传了域名进来，则会解析成 path

        //查看是几级域名
        $data = explode('.', $host);
        $n = count($data);
        //判断是否是双后缀
        $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
        if (($n > 2) && preg_match($preg, $host)) {
            //双后缀取后3位
            $host = $data[$n - 3] . '.' . $data[$n - 2] . '.' . $data[$n - 1];
        } else {
            //非双后缀取后两位
            $host = $data[$n - 2] . '.' . $data[$n - 1];
        }
        return $host;
    }
}

if (! function_exists('isDateTime')) {
    /**
     * 是否为正常的时间格式（一般用于校验前端传值是否正常。若前端传值Invalid date，直接使用会PHP报错，可用此方法校验）.
     */
    function isDateTime(string $dateTime): bool
    {
        $ret = strtotime($dateTime);
        return $ret !== false && $ret != -1;
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.（hyperf官方3.1之后将失效，自己加个）.
     */
    function value(mixed $value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}
if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.（hyperf官方3.1之后将失效，自己加个）.
     */
    function env(string $key, mixed $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return value($default);
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }
        return $value;
    }
}
