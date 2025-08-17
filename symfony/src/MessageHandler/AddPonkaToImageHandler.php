<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class AddPonkaToImageHandler
{
    public function __construct(
        private PhotoPonkaficator $ponkaficator,
        private PhotoFileManager $photoFileManager,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(AddPonkaToImage $addPonkaToImage): void
    {
        $imagePost = $addPonkaToImage->getImagePost();

        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoFileManager->read($imagePost->getFilename())
        );
        $this->photoFileManager->update($imagePost->getFilename(), $updatedContents);
        $imagePost->markAsPonkaAdded();
        $this->entityManager->flush();
    }
}