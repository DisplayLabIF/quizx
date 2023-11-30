<?php

namespace App\Service;

use App\Entity\Quiz\Quiz;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenerateCodigoQuizOuNivelamento
{
    /**
     * @var ContainerInterface
     */
    protected $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getCode()
    {
        $codeQuiz = self::generateCode();

        $em = $this->container->get('doctrine')->getManager();

        while (($em->getRepository(Quiz::class)->findOneBy(['codigo' => $codeQuiz]) !== null)) {
            $codeQuiz = self::generateCode();
        }
        return $codeQuiz;
    }

    private static function generateCode()
    {
        $agora = new \DateTime('now');
        return strtoupper(substr(md5($agora->format('Ymdhis')), 1, 6));
    }
}
