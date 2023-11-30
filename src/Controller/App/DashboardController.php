<?php


namespace App\Controller\App;

use App\Entity\Curso\Matricula;
use App\Repository\CursoRepository;
use App\Repository\LeadQuizRepository;
use App\Repository\MatriculaRepository;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;
use App\Repository\TurmaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractController
{

    /**
     * @Route("/app/dashboard",
     *     name="app_dashboard")
     */
    public function index(Request $request, MatriculaRepository $matriculaRepository, RespostaQuizRepository $respostaQuizRepository, LeadQuizRepository $leadQuizRepository, PaginatorInterface $paginator, QuizRepository $quizRepository)
    {
        $user = $this->getUser();
        $quantidadeTurmas = 0;
        $quantidadeAlunos = 0;
        $quantidadeRespostaQuizes = 0;
        $quantidadeNivelamentos = 0;

        $quantidateLeads = $leadQuizRepository->getQuantidadeLeadsCadastrados($user->getId());
        $ultimoLeadCadastrado = $leadQuizRepository->getUltimoLeadCadastrado($user->getId());

        $ultimosLeadsCadastrados = $leadQuizRepository->getUltimosLeadsCadastrados($user->getId());
        $ultimoLeadRespostaQuiz = $leadQuizRepository->getUltimoLeadRespostaQuiz($user->getId());

        $ultimosLeadsCadastrados = $paginator->paginate(
            $ultimosLeadsCadastrados,
            $request->query->getInt('page', 1),
            30
        );

        foreach ($user->getCursos() as $curso) {
            $quantidadeTurmas += $curso->getTurmas()->count();
        }

        $quantidadeAlunos = $matriculaRepository->getQuantidadeAlunos($user->getId());
        $quantidadeRespostaQuizes = $respostaQuizRepository->getQuantidadeRespostasQuiz($user->getId());

        $showAgendaRecebimentos = false;
        $agendamentos = [];
        $escola = $user->getEscola();

    

        /*$this->addFlash(
            'success',
            "Seja bem vindo ao Quiz Class! Você receberá um e-mail com sua senha de acesso.
                Caso tenha alguma dúvida, envie um e-mail para contato@quizclass.com.br. Comece criando o seu primeiro Quiz!"
        );*/
        return $this->render('app/dashboard/index.html.twig', [
            'nome_usuario' => $this->getUser()->getNome(),
            'quantidade_turmas' => $quantidadeTurmas,
            'quantidade_alunos' => $quantidadeAlunos,
            'quantidade_resposta_quizes' => $quantidadeRespostaQuizes,
            'ultimos_leads_cadastrados' => $ultimosLeadsCadastrados,
            'quantidade_leads' => $quantidateLeads,
            'ultimoLeadRespostaQuiz' => $ultimoLeadRespostaQuiz,
            'ultimoLeadCadastrado' => $ultimoLeadCadastrado,
            'showAgendaRecebimentos' => $showAgendaRecebimentos,
            'agendamentos' =>  $agendamentos,
            'qtd_quizzes' => COUNT($quizRepository->getQuizes($this->getUser()->getId()))
        ]);
    }
}
