<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\ImagePost;

readonly class DeleteImagePost
{
    public function __construct(
        private ImagePost $imagePost,
    ) {}

    public function getImagePost(): ImagePost
    {
        return $this->imagePost;
    }
}
