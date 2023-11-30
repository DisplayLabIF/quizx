<?php

namespace App\Controller\App;

use App\Entity\Curso\Matricula;
use App\Entity\Curso\Turma;
use App\Form\Type\Turma\MatriculaType;
use App\Service\Braspag;
use App\Service\MatricularAlunoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TurmaController
 * @package App\Controller\App
 * @Route("/app/curso")
 */
class MatriculaController extends AbstractController
{
    /**
     * @Route("/turma/{turma_id}/matricula", name="app_matricular_aluno")
     */
    public function matricularAluno(Request $request, MatricularAlunoService $matricularAlunoService)
    {
        $turma_id = $request->get('turma_id');

        $entityManager = $this->getDoctrine()->getManager();
        $turma = $entityManager->getRepository(Turma::class)->find($turma_id);

        $form = $this->createForm(MatriculaType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matricula = $form->getData();

            $matricula['telefone'] = preg_replace('/[^0-9]/', '', $matricula['telefone']);
            $matricula['status'] = 'FINALIZADA';

            try {
                $retorno = $matricularAlunoService->efetuarMatricula($matricula, $turma);

                if ($retorno['status'] === 200) {
                    return $this->redirectToRoute('app_curso_gerenciar_alunos', ['turma_id' => $turma->getID()]);
                } else if ($retorno['status'] === 422) {
                    $this->addFlash(
                        'warning',
                        'E-mail informado já possui matrícula no curso'
                    );
                } else if ($retorno['status'] === 403) {
                    $this->addFlash(
                        'warning',
                        'E-mail informado já possui um cadastro como professor. Informe um novo e-mail, ou um e-mail de aluno.'
                    );
                }
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    $e->getMessage()
                );
            }
        }

        return $this->render('app/matricula/matricularAluno.html.twig', [
            'form' => $form->createView(),
            'turma' => $turma,
        ]);
    }

    /**
     * @Route("/turma/{turma_id}/matricula/{matricula_id}/edit", name="app_matricula_edit")
     */
    public function editarMatriculaAluno(Request $request, MatricularAlunoService $matricularAlunoService)
    {
        $turma_id = $request->get('turma_id');
        $matricula_id = $request->get('matricula_id');

        $entityManager = $this->getDoctrine()->getManager();
        $turma = $entityManager->getRepository(Turma::class)->find($turma_id);
        $matricula = $entityManager->getRepository(Matricula::class)->find($matricula_id);

        $matriculaArray = [
            "nome" => $matricula->getUser()->getNome(),
            "cpf" =>  $matricula->getUser()->getCpf(),
            "telefone" => $matricula->getUser()->getContato()->getCelular(),
            "email" => $matricula->getUser()->getEmail(),
            "cep" => $matricula->getUser()->getEnderecos()->first()->getCep(),
            "logradouro" => $matricula->getUser()->getEnderecos()->first()->getLogradouro(),
            "numero" => $matricula->getUser()->getEnderecos()->first()->getNumero(),
            "complemento" => $matricula->getUser()->getEnderecos()->first()->getComplemento(),
            "bairro" => $matricula->getUser()->getEnderecos()->first()->getBairro(),
            "cidade" => $matricula->getUser()->getEnderecos()->first()->getCidade(),
            "estado" => $matricula->getUser()->getEnderecos()->first()->getEstado(),
            "anexo" => null,
            "checkAluno" => $matricula->getIsAluno(),
            "checkTermos" => true,
            "status" => $matricula->getStatus()
        ];

        $form = $this->createForm(MatriculaType::class,  $matriculaArray, [
            'label_check_aluno' =>  $turma->getDescricaoAssinaturaEspecial() ?  $turma->getDescricaoAssinaturaEspecial() : null,
            'editar_matricula' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matricula = $form->getData();

            $matricula['cpf'] = preg_replace('/[^0-9]/', '', $matricula['cpf']);
            $matricula['telefone'] = preg_replace('/[^0-9]/', '', $matricula['telefone']);
            $matricula['cep'] = preg_replace('/[^0-9]/', '', $matricula['cep']);

            try {
                $retorno = $matricularAlunoService->efetuarMatricula($matricula, $turma, false, $matricula_id);

                if ($retorno['status'] === 200) {
                    return $this->redirectToRoute('app_curso_gerenciar_alunos', ['turma_id' => $turma->getID()]);
                } else if ($retorno['status'] === 422) {
                    $this->addFlash(
                        'warning',
                        'E-mail informado já possui matrícula no curso'
                    );
                } else if ($retorno['status'] === 403) {
                    $this->addFlash(
                        'warning',
                        'E-mail informado já possui um cadastro como professor. Informe um novo e-mail, ou um e-mail de aluno.'
                    );
                }
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    $e->getMessage()
                );
            }
        }

        return $this->render('app/matricula/edit-matriculaAluno.html.twig', [
            'form' => $form->createView(),
            'turma' => $turma,
        ]);
    }

    /**
     * @Route("/turma/matricula/{matricula_id}/cancelar", name="app_cancelar_matricula_aluno")
     */
    public function actionCancelarMatricula(Request $request, Braspag $braspag)
    {
        $matriculaId = $request->get('matricula_id');

        $em = $this->getDoctrine()->getManager();

        $matricula = $em->getRepository(Matricula::class)->find($matriculaId);

        $matricula->setStatus('CANCELADA');

        $em->persist($matricula);

        $em->flush();

        return $this->redirectToRoute('app_curso_gerenciar_aluno_financeiro', ['matricula_id' => $matriculaId]);
    }
}
