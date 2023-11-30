<?php

namespace App\Service;

use App\Entity\Curso\Aula;
use Symfony\Component\String\Slugger\SluggerInterface;
use Vimeo\Vimeo;

class VimeoService
{
    private $viemo;
    private  $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->viemo = new Vimeo($_ENV['CLIENT_ID_VIMEO'], $_ENV['CLIENT_SECRET_VIMEO'], $_ENV['TOKEN_VIMEO']);
        $this->slugger = $slugger;
    }
}
