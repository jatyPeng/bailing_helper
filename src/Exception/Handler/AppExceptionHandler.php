<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Exception\Handler;

use Bailing\Helper\ApiHelper;
use Bailing\Helper\RequestHelper;
use Bailing\Helper\Webhook\FeishuHelper;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    protected StdoutLoggerInterface $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        switch (true) {
            case $throwable instanceof ValidationException:
                $message = $throwable->validator->errors()->first();
                $this->logger->warning('validation: ' . $message);
                $errorData = Json::encode(ApiHelper::genErrorData($message));
                return $response->withHeader('Content-Type', 'application/json;charset=utf-8')->withHeader('Server', 'Hyperf')->withStatus(200)->withBody(new SwooleStream($errorData));
        }
        $errMsg = sprintf('%s in %s[%s] ', $throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
        $this->logger->error($errMsg);
        $this->logger->error($throwable->getTraceAsString());

        if (FeishuHelper::checkConfig()) {
            FeishuHelper::sendMarkDown('php线上代码错误（' . RequestHelper::getClientDomain() . '）', [
                [[
                    'tag' => 'text',
                    'text' => '服务名：' . env('APP_NAME'),
                ]],
                [[
                    'tag' => 'text',
                    'text' => '错误概要：' . $errMsg,
                ]],
                [[
                    'tag' => 'text',
                    'text' => '详细错误：' . $throwable->getTraceAsString(),
                ]],
            ]);
        }

        $errorData = Json::encode(ApiHelper::genErrorData(env('APP_ENV') == 'dev' ? $errMsg : 'Internal Server Error.', ApiHelper::CODE_ERROR));

        return $response->withHeader('Server', 'Hyperf')->withStatus(500)->withBody(new SwooleStream($errorData));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
