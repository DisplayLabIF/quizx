<?php


namespace App\Controller\App;

use App\Entity\Base\Notificacao;
use App\Entity\Escola;
use App\Form\Type\MinhaConta\ContatoEmailType;
use App\Form\Type\MinhaConta\InstituicaoType;
use App\Form\Type\MinhaConta\DadosAcessoType;
use App\Form\Type\MinhaConta\IntegracaoType;
use App\Form\Type\MinhaConta\MeusDadosType;
use App\Form\Type\MinhaConta\NotificacaoType;
use App\Service\RDStationService;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Route("/app/minha-conta/dados-acesso",
     *     name="app_minha_conta_dados_acesso")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $form = $this->createForm(DadosAcessoType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user->setNome($form->get('nome')->getData());
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
            return $this->redirectToRoute('app_minha_conta_dados_acesso');
        }

        return $this->render('app/minhaConta/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/minha-conta/meus-dados",
     *     name="app_minha_conta_meus_dados")
     */
    public function meusDados(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $originalEnderecos = new ArrayCollection();

        foreach ($user->getEnderecos() as $endereco) {
            $originalEnderecos->add($endereco);
        }


        $form = $this->createForm(MeusDadosType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            foreach ($originalEnderecos as $endereco) {
                if (false === $user->getEnderecos()->contains($endereco)) {
                    $user->removeEndereco($endereco);
                    $entityManager->remove($endereco);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Salvo!'
            );

            return $this->redirectToRoute('app_minha_conta_meus_dados');
        }

        return $this->render('app/minhaConta/meusDados.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/minha-conta/instituicao",
     *     name="app_minha_conta_instituicao")
     */
    public function instituicao(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $escola = null;
        if ($user && $user->getEscola()) {
            $escola = $entityManager->getRepository(Escola::class)->find($user->getEscola());
        } else {
            $escola = new Escola();
            $escola->setCreatedBy($this->getUser());
        }

        $originalCursos = new ArrayCollection();

        foreach ($escola->getCursosDisponiveisVenda() as $curso) {
            $originalCursos->add($curso);
        }


        $form = $this->createForm(InstituicaoType::class, $escola, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $escola = $form->getData();
            foreach ($originalCursos as $curso) {
                if (false === $escola->getCursosDisponiveisVenda()->contains($curso)) {
                    $curso->setEscola(null);
                }
            }
            foreach ($form->get('cursosDisponiveisVenda')->getData() as $curso) {
                $curso->setEscola($escola);
            }
            $user->setEscola($escola);
            if (!$escola->getCreatedBy())
                $escola->setCreatedBy($this->getUser());
            $entityManager->persist($escola);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Salvo!'
            );
            return $this->redirectToRoute('app_minha_conta_instituicao');
        }

        return $this->render('app/minhaConta/instituicao.html.twig', [
            'form' => $form->createView(),
            'user_id' => $this->getUser()->getId(),
            'quizclass_api' => $_ENV['QUIZCLASS_API'],
        ]);
    }

    /**
     * @Route("/app/minha-conta/integracao",
     *     name="app_minha_conta_integracao")
     */
    public function integracao(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $escola = null;

        if ($user && $user->getEscola()) {
            $escola = $user->getEscola();
        } else {
            $escola = new Escola();
            $escola
                ->setNome('')
                ->setUrl('')
                ->setCnpj('')
                ->setEmail('')
                ->setCreatedBy($this->getUser());
        }
        $clientId = $escola->getRdStationClientId();
        $clientSecret = $escola->getRdStationClientSecret();

        $form = $this->createForm(IntegracaoType::class, $escola);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $escolaForm = $form->getData();

            $user->setEscola($escolaForm);
            if (!$escolaForm->getCreatedBy())
                $escolaForm->setCreatedBy($this->getUser());

            $entityManager->persist($escolaForm);
            $entityManager->persist($user);
            $entityManager->flush();

            if (
                $escolaForm->getRdStationClientId() && $escolaForm->getRdStationClientId() != '' &&
                $escolaForm->getRdStationClientId() !== $clientId &&
                $escolaForm->getRdStationClientSecret() && $escolaForm->getRdStationClientSecret() != '' &&
                $escolaForm->getRdStationClientSecret() !== $clientSecret
            ) {
                return $this->redirect("{$_ENV['RDSTATION_API']}/auth/dialog?client_id={$escolaForm->getRdStationClientId()}&redirect_uri={$_ENV['RDSTATION_URL_CALLBACK']}");
            }

            $this->addFlash(
                'notice',
                'Salvo!'
            );

            return $this->redirectToRoute('app_minha_conta_integracao');
        }

        return $this->render('app/minhaConta/integracao.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/app/rdstation/callback",
     *     name="rdstation_callback")
     */
    public function callbackRdStation(Request $request, RDStationService $rDStationService)
    {
        $em = $this->getDoctrine()->getManager();
        $code = $request->get('code');

        $escola = $this->getUser()->getEscola();
        $escola->setRdStationCode($code);
        $em->persist($escola);
        $em->flush();

        $rDStationService->auth($escola);

        $this->addFlash(
            'notice',
            'Autenticação realizada com sucesso!'
        );

        return $this->redirectToRoute('app_minha_conta_integracao');
    }

    /**
     * @Route("/app/minha-conta/email",
     *     name="app_minha_conta_email")
     */
    public function email(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $escola = null;

        if ($user && $user->getEscola()) {
            $escola = $user->getEscola();
        } else {
            $escola = new Escola();
            $escola
                ->setNome('')
                ->setUrl('')
                ->setCnpj('')
                ->setEmail('')
                ->setCreatedBy($this->getUser());
        }

        $form = $this->createForm(ContatoEmailType::class, $escola);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $escola = $form->getData();

            $user->setEscola($escola);
            if (!$escola->getCreatedBy())
                $escola->setCreatedBy($this->getUser());

            $entityManager->persist($escola);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Salvo!'
            );

            return $this->redirectToRoute('app_minha_conta_email');
        }

        return $this->render('app/minhaConta/email.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/app/minha-conta/notificacoes",
     *     name="app_minha_conta_notificacoes")
     */
    public function notificacao(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $notificacao = $user->getNotificacao();

        if (!$notificacao)
            $notificacao = new Notificacao();

        $form = $this->createForm(NotificacaoType::class, $notificacao);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $notificacao = $form->getData();

            $user->setNotificacao($notificacao);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Salvo!'
            );

            return $this->redirectToRoute('app_minha_conta_notificacoes');
        }

        return $this->render('app/minhaConta/notificacao.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/minha-conta/dados/delete",
     *     name="app_minha_conta_dados_acesso_delete")
     */
    public function exclusaoLogicaUsuario()
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        // foreach ($user->getCursos() as $curso) {
        //     foreach ($curso->getTurmas() as $turma) {
        //         $turma->setActive(false);
        //     }
        //     $curso->setActive(false);
        // }
        // $user->getEscola()->setActive(false);

        $user->setActive(false);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_logout');
    }
}
