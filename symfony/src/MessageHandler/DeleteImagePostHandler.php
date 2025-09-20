<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DeleteImagePost;
use App\Message\DeletePhotoFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class DeleteImagePostHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(DeleteImagePost $deleteImagePost): void
    {
        $imagePost = $deleteImagePost->getImagePost();

        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new DeletePhotoFile(
            $imagePost->getFilename())
        );
    }
}