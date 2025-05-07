<?php

namespace Support;

use App\Events\AfterSessionRegenerated;
use Closure;

final class SessionRegenerator
{
    public static function run(?Closure $callback = null) : void 
    {
        // dd(request()->session()->getId());
        $oldSessionId = request()->session()->getId();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        if (!is_null($callback)) {
            $callback();
        }

        event(new AfterSessionRegenerated(
            oldSessionId: $oldSessionId,
            newSessionId: request()->session()->getId()
        ));
    }
}