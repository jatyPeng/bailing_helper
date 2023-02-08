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
        $errMsg = sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile());
        $this->logger->error($errMsg);
        $this->logger->error($throwable->getTraceAsString());

        $errorData = Json::encode(ApiHelper::genErrorData(env('APP_ENV') == 'dev' ? $errMsg : 'Internal Server Error.', ApiHelper::CODE_ERROR));

        return $response->withHeader('Server', 'Hyperf')->withStatus(500)->withBody(new SwooleStream($errorData));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
