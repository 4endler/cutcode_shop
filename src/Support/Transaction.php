<?php

namespace Support;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

final class Transaction
{
    public static function run(
        Closure $callback,
        ?Closure $finished = null,
        ?Closure $onError = null,
    )
    {
        try {
            DB::beginTransaction();

            $result = $callback();

            if ($finished) {
                $finished($result);
            }

            DB::commit();
            return $result;            
        } catch (Throwable $e) {
            DB::rollBack();
            if ($onError) {
                $onError($e);
            }

            throw $e;
        }
    }
}