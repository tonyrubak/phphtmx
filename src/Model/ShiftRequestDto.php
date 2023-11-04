<?php
// src/Model/ShiftRequestDto.php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ShiftRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
            public string $employee,
        #[Assert\NotBlank]
            public string $shift,
        #[Assert\NotBlank]
            public int $formDate,
        #[Assert\NotBlank]
            public string $requestType
    ) {
    }
}
