<?php

declare(strict_types=1);

namespace App\Message;

readonly class DeletePhotoFile
{
    public function __construct(
        private string $filename,
    ) {
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}