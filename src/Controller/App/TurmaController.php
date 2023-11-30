<?php

namespace App\Controller\App;

use App\Entity\Curso\AulaPresenca;
use App\Entity\Admin\Compra;
use App\Entity\Curso\Curso;
use App\Entity\Curso\HorarioData;
use App\Entity\Curso\Turma;
use App\Form\Type\Turma\ChamadaType;
use App\Form\Type\Turma\TurmaType;
use App\Service\Braspag;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TurmaController
 * @package App\Controller\App
 * @Route("/app/curso")
 */
class TurmaController extends AbstractController
{
    /**
     * @Route("/{curso_id}/gerenciar", name="app_curso_gerenciar")
     */
    public function index(Request $request)
    {
        $curso_id = $request->get('curso_id');

        $entityManager = $this->getDoctrine()->getManager();
        $curso = $entityManager->getRepository(Curso::class)->find($curso_id);

        return $this->render('app/turma/index.html.twig', [
            'curso' => $curso
        ]);
    }

    /**
     * @Route("/{curso_id}/nova-turma", name="app_nova_turma")
     */
    public function create(Request $request)
    {
        $curso_id = $request->get('curso_id');

        $entityManager = $this->getDoctrine()->getManager();
        $curso = $entityManager->getRepository(Curso::class)->findOneBy(['id' => $curso_id]);

        $turma = new Turma();

        //$turma->setCodigo($this->getCode($turma->getId()));

        $form = $this->createForm(TurmaType::class, $turma, ['user' => $this->getUser()->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $turma = $form->getData();
            $turma->setCurso($curso);

            $this->criaHorarios($turma);

            $entityManager->persist($turma);
            $entityManager->flush();
            return $this->redirectToRoute('app_edit_turma', ['turma_id' => $turma->getId()]);
        }

        return $this->render('app/turma/nova-turma.html.twig', [
            'form' => $form->createView(),
            'curso_id' => $curso_id
        ]);
    }

    /**
     * @Route("/edit-turma/{turma_id}", name="app_edit_turma")
     */
    public function edit(Request $request)
    {
        $turma_id = $request->get('turma_id');

        $entityManager = $this->getDoctrine()->getManager();
        $turma = $entityManager->getRepository(Turma::class)->find($turma_id);
        $originalHorarios = new ArrayCollection();
        $originalHorarioDatas = new ArrayCollection();

        foreach ($turma->getHorarios() as $horario) {
            $originalHorarios->add($horario);
        }
        foreach ($turma->getHorarioDatas() as $HorarioData) {
            $originalHorarioDatas->add($HorarioData);
        }

        $form = $this->createForm(TurmaType::class, $turma, ['user' => $this->getUser()->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $turma = $form->getData();

            $this->criaHorarios($turma, $originalHorarios);

            foreach ($originalHorarios as $horario) {
                if (false === $turma->getHorarios()->contains($horario)) {
                    // $turma->removeHorario($horario);
                    // $horario->setTurma(null);
                    // $entityManager->remove($horario);
                    $horario->setActive(false);
                }
            }
            foreach ($originalHorarioDatas as $horarioData) {
                if (false === $turma->getHorarioDatas()->contains($horarioData)) {
                    $horarioData->setActive(false);
                    $entityManager->persist($horarioData);
                }
            }
            $turma->setUpdated(new \DateTime('now'));
            $entityManager->persist($turma);
            $entityManager->flush();

            return $this->redirectToRoute('app_curso_gerenciar', ['curso_id' => $turma->getCurso()->getID()]);
        }

        return $this->render('app/turma/edit-turma.html.twig', [
            'form' => $form->createView(),
            'curso_id' => $turma->getCurso()->getId()
        ]);
    }

    /**
     * @Route("/gerenciar/{turma_id}/alunos", name="app_curso_gerenciar_alunos")
     */
    public function alunosTurma(Request $request)
    {
        $turma_id = $request->get('turma_id');

        $entityManager = $this->getDoctrine()->getManager();
        $turma = $entityManager->getRepository(Turma::class)->find($turma_id);

        $tiposStatus = new \App\Utils\ValueObjects\TiposStatus();
        $TiposStatusMatricula = $tiposStatus->getLabelTiposStatusMatricula();

        return $this->render('app/turma/alunos.html.twig', [
            'turma' => $turma,
            'TiposStatusMatricula' => $TiposStatusMatricula
        ]);
    }

    /**
     * @Route("/gerenciar/turma/{matricula_id}/financeiro", name="app_curso_gerenciar_aluno_financeiro")
     */
    public function financeiro(Request $request)
    {
        $matricula_id = $request->get('matricula_id');

        $entityManager = $this->getDoctrine()->getManager();
        $compras = $entityManager->getRepository(Compra::class)->findBy(['matricula' => $matricula_id], ['descricao' => 'DESC']);

        $turma = null;
        if ($compras[0]) {
            $turma = $compras[0]->getMatricula()->getTurma();
        }

        $tiposStatus = new \App\Utils\ValueObjects\TiposStatus();
        $tiposStatusParcela = $tiposStatus->getLabelTiposStatusParcela();

        return $this->render('app/turma/financeiro.html.twig', [
            'compras' => $compras,
            'turma' => $turma,
            'tiposStatusParcela' => $tiposStatusParcela,
            'matricula_id' => $matricula_id,
            'quizclass_api' => $_ENV['QUIZCLASS_API']
        ]);
    }

    /**
     * @Route("/{turma_id}/aulas", name="app_curso_gerenciar_aulas")
     */
    public function aulas(Request $request)
    {
        $turma_id = $request->get('turma_id');

        $entityManager = $this->getDoctrine()->getManager();
        $turma = $entityManager->getRepository(Turma::class)->find($turma_id);

        return $this->render('app/turma/aulas.html.twig', [
            'turma' => $turma
        ]);
    }

    /**
     * @Route("/turma/{aula_id}/chamada", name="app_curso_turma_aulas_chamada")
     */
    public function chamada(Request $request)
    {
        $aula_id = $request->get('aula_id');

        $entityManager = $this->getDoctrine()->getManager();
        $horarioData = $entityManager->getRepository(HorarioData::class)->find($aula_id);
        if (!$horarioData->getChamada()) {
            foreach ($horarioData->getTurma()->getMatriculas() as $matricula) {
                if ($matricula->getStatus() !== 'FINALIZADA')
                    continue;
                $newPresenca = new AulaPresenca();
                $newPresenca->setView(0);
                $matricula->addAulaPresenca($newPresenca);
                $horarioData->addAulaPresenca($newPresenca);
            }
        }

        $form = $this->createForm(ChamadaType::class, $horarioData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $horarioData = $form->getData();
            $horarioData->setChamada(true);
            $entityManager->persist($horarioData);
            $entityManager->flush();

            return $this->redirectToRoute('app_curso_gerenciar_aulas', ['turma_id' => $horarioData->getTurma()->getID()]);
        }

        return $this->render('app/turma/chamada.html.twig', [
            'form' => $form->createView(),
            'horarioData' => $horarioData
        ]);
    }

    /**
     * @Route("/turma/{aula_id}/ao-vivo", name="app_curso_turma_aula_aovivo")
     */
    public function aulaAoVivo(Request $request)
    {
        $aula_id = $request->get('aula_id');
        $em = $this->getDoctrine()->getManager();
        $aulaAoVivo = $em->getRepository(HorarioData::class)->find($aula_id);


        return $this->render('app/turma/aula-aovivo.html.twig', [
            'aulaAoVivo' => $aulaAoVivo
        ]);
    }

    private function criaHorarios(Turma $turma, $originalHorarios = null)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $horariosForm = $turma->getHorarios();
        $qtdAulas = $turma->getQuantidadeAulas();
        $dataAula = !empty($turma->getDataInicio()) ? \DateTime::createFromFormat("Y-m-d", $turma->getDataInicio()->format('Y-m-d')) : new \DateTime('now');

        $novasAulas = new ArrayCollection();
        if ($originalHorarios == null) {
            $novasAulas = $horariosForm;
        } else {
            foreach ($horariosForm as $horarioForm) {
                if (false === $originalHorarios->contains($horarioForm)) {
                    $novasAulas->add($horarioForm);
                }
            }
        }


        if (!$novasAulas->isEmpty()) {
            $countQtdAulas = 0;
            do {
                foreach ($novasAulas as $horarioForm) {
                    if ($dataAula->format('D') == $horarioForm->getDia()) {
                        $horarioTurma = new HorarioData();
                        $horarioTurma
                            ->setTurma($turma)
                            ->setHoraInicio($horarioForm->getHoraInicio())
                            ->setHoraTermino($horarioForm->getHoraTermino())
                            ->setHorario($horarioForm)
                            ->setDataAula(\DateTime::createFromFormat("Y-m-d", $dataAula->format('Y-m-d')));

                        $entityManager->persist($horarioTurma);
                        $countQtdAulas++;
                        break;
                    }
                }
                $dataAula->modify('+1 day');
            } while ($countQtdAulas < $qtdAulas);
        }
        return $turma;
    }

    private function getCode($turmaId)
    {
        $codigoTurma = self::gerarCodigo($turmaId);
        $em = $this->getDoctrine()->getManager();

        while ($em->getRepository(Turma::class)->findOneBy(['codigo' => $codigoTurma]) !== null) {
            $codigoTurma = self::gerarCodigo($turmaId);
        }
        return $codigoTurma;
    }

    private static function gerarCodigo($turmaId)
    {
        $codigoTurma = new \DateTime('now');
        $codigoTurma = $codigoTurma->format('YmdHis');
        $codigoTurma = sha1($codigoTurma . $turmaId);
        $codigoTurma = substr($codigoTurma, 0, 4);

        return $codigoTurma;
    }
}
