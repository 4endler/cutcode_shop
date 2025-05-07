<?php

namespace Domain\Cart\StorageIdentities;

use Domain\Cart\Contracts\CartIdentityStorageContract;

final class FakeSessionIdentityStorage implements CartIdentityStorageContract
{
    public function get():string
    {
        return 'test_session_id';
    }
}