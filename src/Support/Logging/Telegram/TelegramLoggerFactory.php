<?php

namespace Support\Logging\Telegram;

use Monolog\Logger;
use Support\Logging\Telegram\TelegramLoggerHandler;

final class TelegramLoggerFactory
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('telegram');
        $logger->pushHandler(new TelegramLoggerHandler($config));
        return $logger;
    }
}