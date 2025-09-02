<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DeleteImagePost;
use App\Photo\PhotoFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteImagePostHandler
{
    public function __construct(
        private PhotoFileManager $photoFileManager,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(DeleteImagePost $deleteImagePost): void
    {
        $imagePost = $deleteImagePost->getImagePost();

        $this->photoFileManager->deleteImage($imagePost->getFilename());

        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();
    }
}