<?php


namespace App\Controller\Aluno;

use App\Form\Type\MinhaConta\DadosAcessoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MinhaContaController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/aluno/minha-conta/dados",
     *     name="app_minha_conta_dados_acesso_aluno")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $form = $this->createForm(DadosAcessoType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setNome($form->get('nome')->getData());
            if ($form->get('password')->getData() !== null && $form->get('password')->getData() !== '') {
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                ));
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Salvo!'
            );
            return $this->redirectToRoute('app_minha_conta_dados_acesso_aluno');
        }

        return $this->render('aluno/minhaConta/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
