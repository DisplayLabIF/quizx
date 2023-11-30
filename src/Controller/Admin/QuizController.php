<?php


namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuizRepository;
use Knp\Component\Pager\PaginatorInterface;

class QuizController extends AbstractController
{

    /**
     * @Route("/admin/quiz",
     *     name="admin_quiz_index")
     */
    public function index(Request $request, PaginatorInterface $paginator, QuizRepository $quizRepository)
    {

        $quizes = $quizRepository->getQuizes($this->getUser()->getId(), $this->getUser()->getTipo());

        $pagination = $paginator->paginate(
            $quizes,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('app/quiz/index.html.twig', [
            'pagination' => $pagination,
            'user_id' => $this->getUser()->getId(),
            'quizclass_api' => $_ENV['QUIZCLASS_API']
        ]);
    }
}
