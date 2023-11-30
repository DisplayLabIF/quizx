<?php


namespace App\Controller\App;

use App\Entity\Quiz\CamposPersonalizados;
use App\Entity\Quiz\ConfiguracaoMarketingQuiz;
use App\Entity\Quiz\ConfiguracaoQuiz;
use App\Entity\Quiz\PersonalizacaoEmail;
use App\Entity\Quiz\PersonalizacaoQuiz;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\RespostaQuiz;
use App\Entity\User;
use App\Form\Type\Quiz\ConfigMarketingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\Quiz\ConfiguracaoQuizType;
use App\Form\Type\Quiz\PersonalizacaoEmailType;
use App\Form\Type\Quiz\PersonalizacaoQuizType;
use App\Form\Type\Resposta\RespostaQuizType;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;
use App\Service\BuildConfigPdf;
use App\Service\EnviarResultadoEmailService;
use App\Service\RDStationService;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends AbstractController
{

    /**
     * @Route("/app/admin/quiz",
     *     name="app_quiz_index")
     */
    public function index(Request $request, PaginatorInterface $paginator, QuizRepository $quizRepository)
    {

        $quizes = $quizRepository->getQuizes($this->getUser()->getId());

        $pagination = $paginator->paginate(
            $quizes,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('app/quiz/index.html.twig', [
            'pagination' => $pagination,
            'user_id' => $this->getUser()->getId()
        ]);
    }

    /**
     * @Route("/app/criar-quiz",
     *     name="app_criar_quiz")
     */
    public function create(Request $request)
    {
        return $this->render('app/quiz/quiz.html.twig', [
            'user_id' => $this->getUser() ? $this->getUser()->getId() : null
        ]);
    }

    /**
     * @Route("/app/{quiz_id}/quiz",
     *     name="app_editar_quiz")
     */
    public function edit(Request $request)
    {
        $quizId = $request->get('quiz_id');

        return $this->render('app/quiz/quiz.html.twig', [
            'user_id' => $this->getUser() ? $this->getUser()->getId() : null,
            'quiz_id' => $quizId
        ]);
    }

    /**
     * @Route("/app/quiz/delete/{quiz_id}",
     *     name="app_delete_quiz")
     */
    public function delete(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
        $quiz->setActive(false);

        $entityManager->persist($quiz);
        $entityManager->flush($quiz);

        return $this->redirectToRoute('app_quiz_index');
    }

    /**
     * @Route("/app/{quiz_id}/quiz-visualizar",
     *     name="app_visualizar_quiz")
     */
    public function visualizarQuiz(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);

        return $this->render('app/quiz/visualizar-quiz.html.twig', [
            'user_id' => $this->getUser() ? $this->getUser()->getId() : null,
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("/app/{quiz_id}/quiz-opcoes",
     *     name="app_opcoes_quiz")
     */
    public function opcoesQuiz(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
        return $this->render('app/quiz/opcoes-quiz.html.twig', [
            'user_id' => $this->getUser() ? $this->getUser()->getId() : null,
            'quiz_id' => $quizId,
            'quiz_codigo' => $quiz->getCodigo()
        ]);
    }

    /**
     * @Route("/app/quiz/finalizar/{quiz_id}",
     *     name="app_quiz_finalizar")
     */
    public function finalizarQuiz(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
        $configuracaoQuiz = $quiz->getConfiguracaoQuiz();

        if (!$quiz->getUser()) {
            $quiz->setUser($this->getUser());
            $entityManager->persist($quiz);
            $entityManager->flush();
        }

        $pontos = 0;

        foreach ($quiz->getQuestoes() as $questao) {
            $pontos += $questao->getValor();
        }

        if (!$configuracaoQuiz) {
            $configuracaoQuiz = new ConfiguracaoQuiz();
            $configuracaoQuiz
                ->setObterDadosRespondente(true)
                ->setObterNome(true)
                ->setObterEmail(true)
                ->setQuandoObterDados('INICIO')
                ->setMostrarNota('MOSTRAR')
                ->setDefinirTempoResposta(false)
                ->setPodePularQuestao(true)
                ->setMostrarCorrecao(true)
                ->setMostrarGabarito(true)
                ->setConfigurarLandPage(false)
                ->setRedirecionarUsuario(false)
                ->setDefinirNotaMinima(false)
                ->setAdicionarMateriais(false);
        }
        if (!$configuracaoQuiz->getOrdemCampos()) {
            $configuracaoQuiz->setOrdemCampos([
                ["campo" => "Nome"],
                ["campo" => "E-mail"],
                ["campo" => "Telefone"],
                ["campo" => "Empresa"],
                ["campo" => "CNPJ"],
                ["campo" => "CPF"],
                ["campo" => "Cidade"]
            ]);
        }

        foreach ($entityManager->getRepository(CamposPersonalizados::class)->findBy(['createdBy' => $this->getUser(), 'active' => 1])
            as $campo) {
            $in_array = false;
            foreach ($configuracaoQuiz->getOrdemCampos() as $ordem) {
                if ($campo->getNome() == $ordem['campo']) {
                    $in_array = true;
                    break;
                }
            }
            if (!$in_array) {
                $novoCampo = $configuracaoQuiz->getOrdemCampos();
                $novoCampo[] = ["campo" => $campo->getNome()];
                $configuracaoQuiz->setOrdemCampos($novoCampo);
            }
        }

        $ordemCamposAux  = $configuracaoQuiz->getOrdemCampos();

        foreach ($ordemCamposAux as $key => $ordem) {

            if (
                $ordem['campo'] == '' ||
                array_count_values(array_column($ordemCamposAux, 'campo'))[$ordem['campo']] > 1
            ) {
                unset($ordemCamposAux[$key]);
            }
        }
        $configuracaoQuiz->setOrdemCampos($ordemCamposAux);

        $originalMateriais = new ArrayCollection();

        foreach ($configuracaoQuiz->getMateriais() as $material) {
            $originalMateriais->add($material);
        }

        $form = $this->createForm(ConfiguracaoQuizType::class, $configuracaoQuiz, [
            'quizPontuacao' => $pontos,
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ordemCamposArray = explode(',', $form->get('ordemCamposArray')->getData());

            $newOrdem = [];
            foreach ($ordemCamposArray as $ordem) {
                $newOrdem[] = [
                    "campo" => trim($ordem)
                ];
            }

            $configuracaoQuiz = $form->getData();

            $configuracaoQuiz->setOrdemCampos($newOrdem);

            foreach ($originalMateriais as $material) {
                if (false === $configuracaoQuiz->getMateriais()->contains($material)) {
                    $material->setActive(false);
                    $entityManager->persist($material);
                }
            }

            foreach ($configuracaoQuiz->getMateriais() as $material) {
                $material->setConfiguracaoQuiz($configuracaoQuiz);
            }

            $quiz->setConfiguracaoQuiz($configuracaoQuiz);

            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_opcoes_quiz', ['quiz_id' => $quizId]);
        }

        return $this->render('app/quiz/finalizar-quiz.html.twig', [
            'form' => $form->createView(),
            'user_id' => $this->getUser()->getId(),
            'quiz_id' => $quizId,
            'pontos' => $pontos,
            'access_token_vimeo' => $_ENV['TOKEN_VIMEO'],
        ]);
    }

    /**
     * @Route("/export-pdf", name="quiz_export_pdf")
     */
    public function pdfAction(Request $request, BuildConfigPdf $buildConfigPdf)
    {

        $quizId = $request->get('idQuiz');
        $qtdeRowsResp = $request->get('qtdeRowsResp') ? $request->get('qtdeRowsResp') : 5;
        $cabecalhoPdf = nl2br($request->get('cabecalhoPdf'));

        $em = $this->getDoctrine()->getManager();

        $quiz = $em->getRepository(Quiz::class)->find($quizId);

        $pdf = $buildConfigPdf->getConfig();
        $pdf->addPage($this->renderView('app/quiz/pdf.html.twig', [
            'quiz' => $quiz,
            'qtdeRowsResp' => $qtdeRowsResp,
            'cabecalhoPdf' => $cabecalhoPdf,
        ]));

        if (!$pdf->send("Quizx.pdf", true)) {
            echo $pdf->getError();
        }

        exit();
    }

    /**
     * @Route("/app/{quiz_id}/quiz-integrar",
     *     name="app_integrar_quiz")
     */
    public function integrarQuiz(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
        return $this->render('app/quiz/integrar-quiz.html.twig', [
            'quiz_id' => $quizId,
            'quiz_codigo' => $quiz->getCodigo()
        ]);
    }

    /**
     * @Route("/app/{quiz_id}/quiz-personalizar",
     *     name="app_personalizar_quiz")
     */
    public function personalizarQuiz(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
        $personalizar = $quiz->getPersonalizacaoQuiz();
        if (!$personalizar) {
            $personalizar = new PersonalizacaoQuiz();
            $personalizar
                ->setDefaultColor('#ffe132')
                ->setPrimaryColor('#0047ff')
                ->setSecondaryColor('#dadada')
                ->setTextPrimaryColor('#202124')
                ->setTextSecondaryColor('#9F9C9C')
                ->setTextPrimaryColorQuestao('#202124')
                ->setTextSecondaryColorQuestao('#9F9C9C')
                ->setBackgroundColorApresentacao('#fff')
                ->setBackgroundColorQuestao('#fff');
        }

        $form = $this->createForm(PersonalizacaoQuizType::class, $personalizar);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalizar = $form->getData();

            $quiz->setPersonalizacaoQuiz($personalizar);


            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_opcoes_quiz', ['quiz_id' => $quizId]);
        }

        return $this->render('app/quiz/personalizar-quiz.html.twig', [
            'form' => $form->createView(),
            'quiz_id' => $quizId,
            'user_id' => $this->getUser()->getId(),
            'quiz_codigo' => $quiz->getCodigo(),
            'quizclass_api' => $_ENV['QUIZCLASS_API']
        ]);
    }

    /**
     * @Route("/app/{quiz_id}/quiz/resultados",
     *     name="app_resultados_quiz")
     */
    public function resultados(Request $request, PaginatorInterface $paginator, RespostaQuizRepository $respostaQuizRepository)
    {
        $intervalo = $request->get('datefilter');
        $nomeLead = $request->get('aluno_lead');
        $quizId = $request->get('quiz_id');
        $filtarLeadsCapturados = $request->get('filtrar_leads_capturados');
        $respostasQuiz = $respostaQuizRepository->getRespostasQuiz(null, $quizId, $intervalo, $nomeLead, $filtarLeadsCapturados);
        $questaoMaisAcertada = $respostaQuizRepository->getQuestaoMaisAcertadaOuMaisErrada(null, $quizId, $intervalo, $nomeLead, true, $filtarLeadsCapturados);
        $questaoMaisErrada = $respostaQuizRepository->getQuestaoMaisAcertadaOuMaisErrada(null, $quizId, $intervalo, $nomeLead, false, $filtarLeadsCapturados);
        $notaMedia = $respostaQuizRepository->getNotaMedia(null, $quizId, $intervalo, $nomeLead, $filtarLeadsCapturados);

        $pagination = $paginator->paginate(
            $respostasQuiz,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('app/quiz/resultados.html.twig', [
            'pagination' => $pagination,
            'nota_media' => $notaMedia,
            'intervalo' => $intervalo,
            'nome_lead' => $nomeLead,
            'filtrar_leads_capturados' => $filtarLeadsCapturados,
            'questaoMaisAcertada' => $questaoMaisAcertada,
            'questaoMaisErrada' => $questaoMaisErrada,
            'quiz_id' => $quizId
        ]);
    }

    /**
     * @Route("/app/quiz/{quiz_id}/personalizar-email",
     *     name="app_personalizar_email")
     */
    public function personalizarEmail(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
        $personalizar = $quiz->getPersonalizacaoEmail();
        if (!$personalizar) {
            $personalizar = new PersonalizacaoEmail();
            $personalizar
                ->setCor('#ffe132');
            $quiz->setPersonalizacaoEmail($personalizar);
        }

        $form = $this->createForm(PersonalizacaoEmailType::class, $personalizar);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalizar = $form->getData();

            $quiz->setPersonalizacaoEmail($personalizar);

            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_opcoes_quiz', ['quiz_id' => $quizId]);
        }

        return $this->render('app/quiz/personalizar-email.html.twig', [
            'form' => $form->createView(),
            'quiz_id' => $quizId,
            'user_id' => $this->getUser()->getId(),
            'quiz_codigo' => $quiz->getCodigo(),
            'quizclass_api' => $_ENV['QUIZCLASS_API']
        ]);
    }

    /**
     * @Route("/app/quiz/{quiz_id}/exportar-resultados",
     *     name="app_quiz_exportar_resultados")
     */
    public function exportarResultados(Request $request, RespostaQuizRepository $respostaQuizRepository, PaginatorInterface $paginator)
    {
        $quizId = $request->get('quiz_id');
        $intervalo = $request->get('datefilter');
        $nomeLead = $request->get('aluno_lead');
        $limit = $request->query->get('limit');
        $filtarLeadsCapturados = $request->get('filtrar_leads_capturados');

        $spreadsheet = new Spreadsheet();
        $respostasQuiz = $respostaQuizRepository->getRespostasQuiz(null, $quizId, $intervalo, $nomeLead, $filtarLeadsCapturados);

        $pagination = $paginator->paginate(
            $respostasQuiz,
            $request->query->getInt('page', 1),
            $limit
        );

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Data');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'E-mail');
        $sheet->setCellValue('D1', 'Telefone');
        $sheet->setCellValue('E1', 'Empresa');
        $sheet->setCellValue('F1', 'CNPJ');
        $sheet->setCellValue('G1', 'CPF');
        $sheet->setCellValue('H1', 'Cidade');
        $sheet->setCellValue('I1', 'Porcentagem acerto');
        $sheet->setCellValue('J1', 'Nota');
        $sheet->setCellValue('K1', 'Finalizou?');

        foreach ($pagination->getItems() as $key => $resposta) {
            $sheet->setCellValue('A' . ($key + 2), $resposta['resposta']->getCreated()->format('d/m/Y'));
            if ($resposta['resposta']->getLeadQuizEntity()) {
                $sheet->setCellValue('B' . ($key + 2), $resposta['resposta']->getLeadQuizEntity()->getNome());
                $sheet->setCellValue('C' . ($key + 2), $resposta['resposta']->getLeadQuizEntity()->getEmail());
                $sheet->setCellValue('D' . ($key + 2), $resposta['resposta']->getLeadQuizEntity()->getTelefone());
                $sheet->setCellValue('E' . ($key + 2), $resposta['resposta']->getLeadQuizEntity()->getEmpresa());
                $sheet->setCellValue('F' . ($key + 2), $resposta['resposta']->getLeadQuizEntity()->getCnpj());
                $sheet->setCellValue('G' . ($key + 2), $resposta['resposta']->getLeadQuizEntity()->getCpf());
                $sheet->setCellValue('H' . ($key + 2), $resposta['resposta']->getLeadQuizEntity()->getCidade());
            } else if ($resposta['resposta']->getAlunoEntity()) {
                $sheet->setCellValue('B' . ($key + 2), $resposta['resposta']->getAlunoEntity()->getNome());
                $sheet->setCellValue('C' . ($key + 2), $resposta['resposta']->getAlunoEntity()->getEmail());
                $sheet->setCellValue('D' . ($key + 2), '');
                $sheet->setCellValue('E' . ($key + 2), '');
                $sheet->setCellValue('F' . ($key + 2), '');
                $sheet->setCellValue('G' . ($key + 2), '');
                $sheet->setCellValue('H' . ($key + 2), '');
            } else {
                $sheet->setCellValue('B' . ($key + 2), 'Lead não capturado');
                $sheet->setCellValue('C' . ($key + 2), 'Lead não capturado');
                $sheet->setCellValue('D' . ($key + 2), 'Lead não capturado');
                $sheet->setCellValue('E' . ($key + 2), 'Lead não capturado');
                $sheet->setCellValue('F' . ($key + 2), 'Lead não capturado');
                $sheet->setCellValue('G' . ($key + 2), 'Lead não capturado');
                $sheet->setCellValue('H' . ($key + 2), 'Lead não capturado');
            }
            $resposta_certa = 0;
            foreach ($resposta['resposta']->getRespostas() as $respostaQuizQuestao) {
                if ($respostaQuizQuestao->getCorreto() == 1)
                    $resposta_certa++;
            }

            $qtdQuestoes = $resposta['resposta']->getQuizAtual()->getQuestoes()->count();

            $sheet->setCellValue(
                'I' . ($key + 2),
                $resposta_certa . '/' . $qtdQuestoes . "\n (" . round((($resposta_certa * 100) / $qtdQuestoes), 2) . "%)"
            );
            $sheet->setCellValue(
                'J' . ($key + 2),
                "Nota: " . $resposta['resposta']->getNota()
            );

            $sheet->setCellValue('K' . ($key + 2), $resposta['resposta']->getFinalizou() == 1 ? 'SIM' : 'NÃO');
        }
        $sheet->setTitle("Resultados");

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'resultados_quiz.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName);
    }

    /**
     * @Route("/app/quiz/{quiz_id}/configuracao-marketing",
     *     name="app_config_marketing_quiz")
     */
    public function configMarketing(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $entityManager = $this->getDoctrine()->getManager();

        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
        $configMarketing = $quiz->getConfiguracaoMarketingQuiz();
        if (!$configMarketing) {
            $configMarketing = new ConfiguracaoMarketingQuiz();
            $quiz->setConfiguracaoMarketingQuiz($configMarketing);
        }

        $form = $this->createForm(ConfigMarketingType::class, $configMarketing);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configMarketing = $form->getData();

            $quiz->setConfiguracaoMarketingQuiz($configMarketing);

            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_opcoes_quiz', ['quiz_id' => $quizId]);
        }

        return $this->render('app/quiz/config-marketing.html.twig', [
            'form' => $form->createView(),
            'quiz_id' => $quizId,
            'user_id' => $this->getUser()->getId(),
            'quiz_codigo' => $quiz->getCodigo(),
            'quizclass_api' => $_ENV['QUIZCLASS_API']
        ]);
    }

    /**
     * @Route("/app/quiz/{quiz_id}/corrigir-respostas",
     *     name="app_corrigir_respostas_quiz")
     */
    public function corrigirRespostas(Request $request, PaginatorInterface $paginator, RespostaQuizRepository $respostaQuizRepository)
    {
        $intervalo = $request->get('datefilter');
        $nomeLead = $request->get('aluno_lead');
        $quizId = $request->get('quiz_id');
        // $filtarLeadsCapturados = $request->get('filtrar_leads_capturados');
        $respostasQuiz = $respostaQuizRepository->getRespostasQuiz(null, $quizId, $intervalo, $nomeLead, true);

        $pagination = $paginator->paginate(
            $respostasQuiz,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('app/quiz/respostas.html.twig', [
            'pagination' => $pagination,
            'intervalo' => $intervalo,
            'nome_lead' => $nomeLead,
            'quiz_id' => $quizId
        ]);
    }

    /**
     * @Route("/app/quiz/{quiz_id}/corrigir-respostas/{resposta_id}",
     *     name="app_corrigir_resposta_quiz")
     */
    public function corrigirResposta(Request $request, RespostaQuizRepository $respostaQuizRepository)
    {
        $quizId = $request->get('quiz_id');
        $respostaId = $request->get('resposta_id');
        $em = $this->getDoctrine()->getManager();
        $resposta = $respostaQuizRepository->find($respostaId);

        $form = $this->createForm(RespostaQuizType::class, $resposta);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resposta = $form->getData();
            $resposta->setRespostaCorrigida(true);
            $nota = 0;
            foreach ($resposta->getRespostas() as $respostaQuestao) {
                if ($respostaQuestao->getNota()) {
                    $nota += $respostaQuestao->getNota();
                } else if ($respostaQuestao->getCorreto()) {
                    $nota += $respostaQuestao->getQuestao()->getValor();
                }
            }

            $resposta->setNota($nota);

            $em->persist($resposta);
            $em->flush();

            return $this->redirectToRoute('app_corrigir_respostas_quiz', ['quiz_id' => $quizId]);
        }

        return $this->render('app/quiz/corrigir-resposta.html.twig', [
            'form' => $form->createView(),
            'quiz_id' => $quizId
        ]);
    }

    /**
     * @Route("/app/quiz/{quiz_id}/corrigir-respostas/{resposta_id}/enviar-email", 
     * name="enviar_resposta_quiz_correcao")
     */
    public function enviarRespostaEmail(Request $request, EnviarResultadoEmailService $enviarResultadoEmailService)
    {
        $quiz_id = $request->get('quiz_id');
        $resposta_id = $request->get('resposta_id');

        $em = $this->getDoctrine()->getManager();
        $resposta = $em->getRepository(RespostaQuiz::class)->find($resposta_id);

        $destinatario = '';

        if ($resposta->getLeadQuizEntity()) {
            $destinatario = $resposta->getLeadQuizEntity()->getEmail();
        } else {
            $destinatario = $resposta->getAlunoEntity()->getEmail();
        }

        $enviarResultadoEmailService->enviarEmailResultado($destinatario, $resposta->getQuizAtual()->getPersonalizacaoEmail(), $resposta);

        return new Response('Resultado enviado', Response::HTTP_OK);
    }
}
