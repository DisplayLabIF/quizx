<?php

namespace App\Tests\Controller\App;

use App\Entity\Quiz\Quiz;
use App\Service\CorrecaoQuestao;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuizControllerTest extends KernelTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSearchQuizByCode()
    {

        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['codigo' => '1234']);

        $this->assertEquals('teste', $quiz->getNome());
    }

    public function testCorrecaoQuestaoMultiplaEscolhaACertou()
    {
        $container = self::$container;

        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['codigo' => '1234']);

        $questao = $quiz->getQuestoes()[0];

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirMultiplaEscolha($questao->getOpcoes(), $questao->getOpcoes()[0]->getId());


        $this->assertEquals(true, $resultado);
    }

    public function testCorrecaoQuestaoMultiplaEscolhaErrou()
    {
        $container = self::$container;

        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['codigo' => '1234']);

        $questao = $quiz->getQuestoes()[0];

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirMultiplaEscolha($questao->getOpcoes(), $questao->getOpcoes()[1]->getId());


        $this->assertEquals(false, $resultado);
    }

    public function testCorrecaoQuestaoAbertaACertou()
    {
        $container = self::$container;

        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['codigo' => '1234']);

        $questao = $quiz->getQuestoes()[2];

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirAberta($questao->getOpcoes(), 'sim, é verdade!');


        $this->assertEquals(true, $resultado);
    }

    public function testCorrecaoQuestaoAbertaErrou()
    {
        $container = self::$container;

        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['codigo' => '1234']);

        $questao = $quiz->getQuestoes()[2];

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirAberta($questao->getOpcoes(), 'não, é falso!');


        $this->assertEquals(false, $resultado);
    }

    public function testCorrecaoQuestaoVFAcertou()
    {
        $container = self::$container;

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirVF([
            [
                'resposta_correta' => true,
                'selectedFalse' => false,
                'selectedTrue' => true
            ],
            [
                'resposta_correta' => false,
                'selectedFalse' => true,
                'selectedTrue' => false
            ]
        ]);


        $this->assertEquals(2, $resultado);
    }

    public function testCorrecaoQuestaoVFParcial()
    {
        $container = self::$container;

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirVF([
            [
                'resposta_correta' => true,
                'selectedFalse' => false,
                'selectedTrue' => true
            ],
            [
                'resposta_correta' => false,
                'selectedFalse' => false,
                'selectedTrue' => true
            ]
        ]);


        $this->assertEquals(1, $resultado);
    }

    public function testCorrecaoQuestaoVFErrou()
    {
        $container = self::$container;

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirVF([
            [
                'resposta_correta' => true,
                'selectedFalse' => true,
                'selectedTrue' => false
            ],
            [
                'resposta_correta' => false,
                'selectedFalse' => false,
                'selectedTrue' => true
            ]
        ]);


        $this->assertEquals(0, $resultado);
    }

    public function testCorrecaoQuestaoOrdenarAcertou()
    {
        $container = self::$container;

        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['codigo' => '1234']);

        $questao = $quiz->getQuestoes()[3];

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirOrdenar($questao->getOpcoes(), ['a b c d']);


        $this->assertEquals(true, $resultado);
    }

    public function testCorrecaoQuestaoOrdenarErrou()
    {
        $container = self::$container;

        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['codigo' => '1234']);

        $questao = $quiz->getQuestoes()[3];

        $correcaoQuestao = $container->get(CorrecaoQuestao::class);

        $resultado = $correcaoQuestao->corrigirOrdenar($questao->getOpcoes(), ['a b d c']);


        $this->assertEquals(false, $resultado);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
