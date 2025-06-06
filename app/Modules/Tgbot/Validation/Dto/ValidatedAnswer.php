<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Validation\Dto;

class ValidatedAnswer
{
    public function __construct(
        public readonly bool $valid,
        public readonly mixed $data,
        public readonly string $errorMsg = ''
    ) {

    }
}
