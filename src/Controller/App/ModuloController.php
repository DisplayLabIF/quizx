<?php

namespace App\Controller\App;

use App\Entity\Curso\Aula;
use App\Entity\Curso\Curso;
use App\Entity\Curso\Modulo;
use App\Form\Type\Modulo\AulaType;
use App\Repository\ModuloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\S3Service;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TurmaController
 * @package App\Controller\App
 * @Route("/app/curso")
 */
class ModuloController extends AbstractController
{
    /**
     * @Route("/{curso_id}/adicionar-aulas", name="app_curso_gerenciar_adicionar_aulas")
     */
    public function adicionarAulas(Request $request, S3Service $s3Service)
    {
        $curso_id = $request->get('curso_id');
        $entityManager = $this->getDoctrine()->getManager();
        $curso = $entityManager->getRepository(Curso::class)->find($curso_id);

        $aula = new Aula();
        $form = $this->createForm(AulaType::class, $aula, ['curso_id' => $curso_id, 'user' => $this->getUser()->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aula = $form->getData();

            $modulo = $form->get('modulo')->getData();
            if (!$modulo->getCurso()) {
                $modulo->setCurso($curso);
                $entityManager->persist($modulo);
            }

            $aula
                ->setModulo($modulo)
                ->setCreatedBy($this->getUser());


            foreach ($aula->getMateriais() as $material) {
                $material->setAula($aula);
            }

            $entityManager->persist($aula);
            $entityManager->flush();

            return $this->redirectToRoute('app_curso_gerenciar', ['curso_id' => $curso_id]);
        }

        return $this->render('app/modulo/nova-aula.html.twig', [
            'curso_id' => $curso_id,
            'form' => $form->createView(),
            'access_token_vimeo' => $_ENV['TOKEN_VIMEO'],
            'user_id' => $this->getUser()->getId()
        ]);
    }

    /**
     * @Route("/{curso_id}/{aula_id}/editar-aula", name="app_curso_gerenciar_editar_aula")
     */
    public function editarAula(Request $request, S3Service $s3Service)
    {
        $curso_id = $request->get('curso_id');
        $aula_id = $request->get('aula_id');
        $entityManager = $this->getDoctrine()->getManager();

        $curso = $entityManager->getRepository(Curso::class)->find($curso_id);

        $aula = $entityManager->getRepository(Aula::class)->find($aula_id);

        $originalMateriais = new ArrayCollection();

        foreach ($aula->getMateriais() as $material) {
            $originalMateriais->add($material);
        }

        $form = $this->createForm(AulaType::class, $aula, ['curso_id' => $curso_id, 'user' => $this->getUser()->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aula = $form->getData();

            $modulo = $form->get('modulo')->getData();
            if (!$modulo->getCurso()) {
                $modulo->setCurso($curso);
                $entityManager->persist($modulo);
            }

            $aula
                ->setModulo($modulo)
                ->setCreatedBy($this->getUser());


            foreach ($aula->getMateriais() as $material) {
                $material->setAula($aula);
            }

            foreach ($originalMateriais as $material) {
                if (false === $aula->getMateriais()->contains($material)) {
                    // $turma->removeHorario($horario);
                    // $horario->setTurma(null);
                    // $entityManager->remove($horario);
                    $material->setActive(false);
                }
            }

            $entityManager->persist($aula);
            $entityManager->flush();

            return $this->redirectToRoute('app_curso_gerenciar', ['curso_id' => $curso_id]);
        }

        return $this->render('app/modulo/editar-aula.html.twig', [
            'curso_id' => $curso_id,
            'form' => $form->createView(),
            'access_token_vimeo' => $_ENV['TOKEN_VIMEO'],
            'user_id' => $this->getUser()->getId()
        ]);
    }

    /**
     * @Route("/modulos/{curso_id}", name="carregar_modulos_json")
     */
    public function getModulossJson(Request $request)
    {
        $curso_id = $request->get('curso_id');
        $entityManager = $this->getDoctrine()->getManager();
        $curso = $entityManager->getRepository(Curso::class)->find($curso_id);

        $modulos = $entityManager->getRepository(Modulo::class)->findBy(['curso' => $curso]);

        $data = [];
        foreach ($modulos as $modulo) {

            $item = [
                'id' => $modulo->getId(),
                'text' => $modulo->getNome(),
            ];
            if (!in_array($item, $data))
                $data[] = $item;
        }

        $resp = [
            'items' => $data,
            'total_count' => count($data)
        ];


        return new JsonResponse($resp);
    }

    /**
     * @Route("/modulos/{curso_id}/search", name="search_modulos_json")
     */
    public function moduloAutocomplete(Request $request, ModuloRepository $repository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $curso_id = $request->get('curso_id');

        $curso = $entityManager->getRepository(Curso::class)->find($curso_id);

        $q = $request->get('q');
        $matches = $repository->createQueryBuilder('m')
            ->where("m.curso = :curso")
            ->andWhere("m.nome LIKE :searchString")
            ->orderBy('m.nome', 'ASC')
            ->setParameter('curso', $curso)
            ->setParameter('searchString', '%' . $q . '%')
            ->getQuery()
            ->getResult();

        $data = array_map(function (Modulo $modulo) use ($request) {
            return ['id' => $modulo->getId(), 'text' => $modulo->getNome()];
        }, $matches);

        $data = array_values($data);

        $modulos = [
            'results' => $data
        ];
        return new JsonResponse($modulos);
    }
}
