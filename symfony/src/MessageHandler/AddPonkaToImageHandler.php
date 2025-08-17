<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddPonkaToImageHandler
{
    public function __invoke(AddPonkaToImage $addPonkaToImage): void
    {
        dump($addPonkaToImage);
    }
}