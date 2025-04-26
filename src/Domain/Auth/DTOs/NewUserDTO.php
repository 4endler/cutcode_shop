<?php

namespace Domain\Auth\DTOs;

final class NewUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {

    }
}