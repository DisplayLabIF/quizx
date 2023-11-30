<?php


namespace App\Controller\Aluno;

use App\Entity\Curso\Aula;
use App\Entity\Curso\HorarioData;
use App\Entity\Curso\Matricula;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AulaController extends AbstractController
{

    /**
     * @Route("/aluno/aulas/{matricula_id}/{aula_id}",
     *     name="aluno_aula_index")
     */
    public function index(Request $request)
    {
        $matriculaId = $request->get('matricula_id');
        $aulaId = $request->get('aula_id');

        $em = $this->getDoctrine()->getManager();

        $matricula = $em->getRepository(Matricula::class)->find($matriculaId);
        $aula = $em->getRepository(Aula::class)->find($aulaId);

        if ($aula) {
            $matricula->addAulaAssistida($aula);
            $em->persist($matricula);
            $em->flush();

            if ($aula->getTipo() === 'quiz')
                return $this->redirectToRoute('app_responder_quiz', ['codigo' => $aula->getQuiz() ? $aula->getQuiz()->getCodigo() : ' ']);

            return $this->render('aluno/aula/index.html.twig', [
                'aula' => $aula,
                'matricula' => $matricula
            ]);
        } else {
            $aulaAoVivo = $em->getRepository(HorarioData::class)->find($aulaId);

            if ($aulaAoVivo->getAula()) {
                $matricula->addAulaAssistida($aulaAoVivo->getAula());
                $em->persist($matricula);
                $em->flush();
            }

            return $this->render('aluno/aula/index.html.twig', [
                'aulaAoVivo' => $aulaAoVivo,
                'matricula' => $matricula
            ]);
        }
    }
}
