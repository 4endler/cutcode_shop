<?php

namespace Domain\Favorites\StorageIdentities;

use Domain\Favorites\Contracts\FavoriteIdentityStorageContract;

final class SessionIdentityStorage implements FavoriteIdentityStorageContract
{
    public function get():string
    {
        return session()->getId();
    }
}