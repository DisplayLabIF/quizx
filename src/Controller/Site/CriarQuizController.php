<?php


namespace App\Controller\Site;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CriarQuizController extends AbstractController
{

    /**
     * @Route("/criar-quiz/ajuda",
     *     name="site_criar_quiz_ajuda")
     */
    public function help(Request $request)
    {
        return $this->render('Site/criarquiz/ajuda.html.twig', [
            'user_id' => $this->getUser() ? $this->getUser()->getId() : null
        ]);
    }
}
