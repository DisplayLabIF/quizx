<?php


namespace App\Controller\App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfessorController extends AbstractController
{

    /**
     * @Route("/dashboard/professor",
     *     name="app_dashboard_professor")
     */
    public function index(Request $request)
    {
        return $this->render('app/dashboard/index.html.twig');
    }
}
