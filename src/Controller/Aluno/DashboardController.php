<?php


namespace App\Controller\Aluno;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractController
{

    /**
     * @Route("/aluno/dashboard",
     *     name="aluno_dashboard")
     */
    public function index(Request $request)
    {
        return $this->render('aluno/dashboard/index.html.twig', [
            'matriculas' => $this->getUser()->getMatriculas()
        ]);
    }
}
