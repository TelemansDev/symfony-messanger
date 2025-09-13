<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use App\Repository\ImagePostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddPonkaToImageHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private PhotoPonkaficator $ponkaficator,
        private PhotoFileManager $photoFileManager,
        private EntityManagerInterface $entityManager,
        private ImagePostRepository $imagePostRepository,
    ) {}

    public function __invoke(AddPonkaToImage $addPonkaToImage): void
    {
        $imagePostId = $addPonkaToImage->getImagePostId();
        $imagePost = $this->imagePostRepository->find($imagePostId);
        if (null === $imagePost) {
            $this->logger->warning(sprintf('Image post with ID %d not found', $imagePostId));
            return;
        }

        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoFileManager->read($imagePost->getFilename())
        );
        $this->photoFileManager->update($imagePost->getFilename(), $updatedContents);
        $imagePost->markAsPonkaAdded();

        $this->entityManager->persist($imagePost);
        $this->entityManager->flush();
    }
}