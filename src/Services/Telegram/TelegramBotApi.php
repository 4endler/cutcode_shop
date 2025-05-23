<?php

namespace Services\Telegram;

use Services\Telegram\Exceptions\TelegramBotApiException;
use Illuminate\Support\Facades\Http;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatId, string $message): bool 
    {
        try {
            $response = Http::get(self::HOST.$token.'/sendMessage', [
                'chat_id' => $chatId,
                'text' => $message
            ])->json();

            return $response['ok'] ?? false;
        } catch (\Throwable $e) {
            report( new TelegramBotApiException($e->getMessage()) );
            return false;     
        }
    }
}