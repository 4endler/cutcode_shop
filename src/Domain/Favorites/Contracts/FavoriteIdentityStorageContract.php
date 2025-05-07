<?php

namespace Domain\Favorites\Contracts;

interface FavoriteIdentityStorageContract
{
    public function get(): string;
}