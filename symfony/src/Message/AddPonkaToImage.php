<?php

declare(strict_types=1);

namespace App\Message;

readonly class AddPonkaToImage
{
    public function __construct(
        private int $imagePostId,
    ) {}

    public function getImagePostId(): int
    {
        return $this->imagePostId;
    }
}
