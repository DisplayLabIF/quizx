<?php


namespace App\Controller\Admin;

use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard",
     *     name="admin_dashboard")
     */
    public function index(Request $request, UserRepository $userRepository, QuizRepository $quizRepository, PaginatorInterface $paginator)
    {
        $usuarios = $userRepository->findBy(['active' => 1], ['qtdLogin' => 'DESC']);

        $pagination = $paginator->paginate(
            $usuarios,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/dashboard/index.html.twig', [
            'qtd_cadastros' => $userRepository->qtdCadastros(),
            'qtd_quizes' => $quizRepository->qtdQuizesCriados(),
            'pagination' => $pagination
        ]);
    }
}
