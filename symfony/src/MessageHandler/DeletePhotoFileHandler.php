<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DeletePhotoFile;
use App\Photo\PhotoFileManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeletePhotoFileHandler
{
    public function __construct(
        private PhotoFileManager $photoFileManager,
    ) {
    }

    public function __invoke(DeletePhotoFile $deletePhotoFile): void
    {
        $this->photoFileManager->deleteImage($deletePhotoFile->getFilename());
    }
}