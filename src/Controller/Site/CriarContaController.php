<?php


namespace App\Controller\Site;

use App\Entity\Base\Contato;
use App\Entity\User;
use App\Entity\User\Professor;
use App\Form\Type\Registro\CadastroType;
use App\Security\LoginFormAuthenticator;
use App\Service\PlanoAcessoService;
use App\Service\RDStationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class CriarContaController extends AbstractController
{

    use TargetPathTrait;

    private $passwordEncoder;
    private $session;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
    }

    /**
     * @Route("/cadastrar-professor", name="site_criar_quiz_cadastrar")
     */
    public function cadastrar(Request $request, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator)
    {
        $type =  $request->get('_route') === 'marketing_cadastrar' ? 'MARKETING_FLEX' : $request->get('type');
        $user = new Professor();
        $em = $this->getDoctrine()->getManager();
        $user->setTipoUsuario('PROFESSOR');
        $form = $this->createForm(CadastroType::class, $user);

        $form->handleRequest($request);

        if (!empty($request->get('email'))) {

            $userCadastrado = $em->getRepository(User::class)->findOneBy(['email' => trim($request->get('email'))]);

            if ($userCadastrado) {
                $this->addFlash(
                    'warning',
                    "Este e-mail já está cadastrado. Infome outro e-mail ou clique em 'Entrar' e faça login ou, caso tenha esquecido sua senha, peça uma nova."
                );
                return $this->render('Site/landPage/cadastrar.html.twig', [
                    'form' => $form->createView(),
                    'type' => $type
                ]);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {

            //$reCapthca = $this->getCaptcha($request->get('recaptchaToken'));

            //if ($_ENV['APP_ENV'] === 'dev' || ($reCapthca->success === true && $reCapthca->score > 0.5)) {

            $email = $request->get('email');

            $user = $em->getRepository(User::class)
                ->findOneBy(['email' => trim($email)]);

            if (!$user) {

                $user = $form->getData();

                $user
                    ->setEmail($email)
                    ->setPassword($this->passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    ))
                    ->setRoles(['ROLE_ADMIN']);

                    $this->saveTargetPath($this->session, 'main', $this->generateUrl('app_dashboard'));
                    $this->addFlash(
                        'success',
                        "Seja bem vindo ao QuizX!"
                    );


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $loginFormAuthenticator,
                    'main'
                );
            }

            $this->addFlash(
                'success',
                "Olá, {$user->getNome()}! Você já está cadastrado no QuizX 😀. Faça login ou caso esqueceu sua senha, clique em " . '"Esqueceu a senha?".'
            );

            return $this->redirectToRoute('app_login', ['tipo' => 'professor']);
            
        }

        return $this->render('Site/criarquiz/cadastrar.html.twig', [
            'form' => $form->createView(),
            'message' => $request->get('messageType'),
            'type' => $type
        ]);
    }

    /**
     * @Route("/app/criar-quiz/cadastrar",
     *     name="criar_quiz_cadastrar")
     */
    public function criarQuizCadastrar(Request $request, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, RDStationService $rDStationService)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $type = $request->get('quiz_type');
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);

        if (!$user) {
            $user = new AdmEscola();
            $user
                ->setNome($request->get('nome'))
                ->setEmail($request->get('email'))
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    $request->get('senha')
                ))
                ->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->enviarLeadRd($rDStationService, $request->get('nome'), $request->get('email'));

            $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );

            return $this->redirectToRoute('app_quiz_finalizar', ['quiz_id' => $request->get('quiz_id'), 'type' => $type]);
        }

        $this->addFlash(
            'success',
            "Olá, {$user->getNome()}! Você já está cadastrado no Quiz Class 😀. Faça login para continuar a configurar seu quiz, ou caso esqueceu sua senha, clique em " . '"Esqueceu a senha?".'
        );

        $redirectRoute = $this->generateUrl('app_quiz_finalizar', ['quiz_id' => $request->get('quiz_id'), 'type' => $type]);


        $this->saveTargetPath($this->session, 'app', $redirectRoute);

        return $this->redirectToRoute('app_login', ['tipo' => 'professor']);
    }

    /**
     * @Route("/save-lead", name="site_save_lead")
     */
    public function saveLeadAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, PlanoAcessoService $planoAcessoService)
    {
        $reCapthca = $this->getCaptcha($request->get('recaptchaToken'));

        // if ($_ENV['APP_ENV'] === 'dev' || ($reCapthca->success === true && $reCapthca->score > 0.5)) {
        $senha = self::generateCode();

        $em = $this->getDoctrine()->getManager();

        $professorCadastrado = $em->getRepository(User::class)
            ->findOneBy(['email' => trim($request->get('email'))]);

        if ($professorCadastrado === null) {
            $professor = new AdmEscola();
            $professor->setNome($request->get('nome'))
                ->setEmail($request->get('email'))
                ->setPassword($passwordEncoder->encodePassword(
                    $professor,
                    $senha
                ))
                ->setPlano((!empty($request->get('plan'))) ? $request->get('plan') : 'FREE')
                ->setRoles(['ROLE_ADMIN']);

            $contato = new Contato();

            $contato->setCelular($request->get('celular'));

            $professor->setContato($contato);

            $em->persist($professor);
            $em->flush();

            $this->registrarPlanoMarketingFlex($request->get('plan'), $professor, $planoAcessoService);

            $this->obrigadoAction($senha, $request->request->get('email'), $request->get('nome'));

            $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $professor,
                $request,
                $loginFormAuthenticator,
                'main'
            );

            return $this->redirectToRoute('app_dashboard');
        }

        $this->addFlash(
            'success',
            "Olá, {$professorCadastrado->getNome()}! Você já está cadastrado no Quiz Class 😀. Faça login ou caso esqueceu sua senha, clique em " . '"Esqueceu a senha?".'
        );

        return $this->redirectToRoute('app_login', ['tipo' => 'professor']);
        // } else {
        //     $this->addFlash(
        //         'danger',
        //         "Erro ao cadastrar"
        //     );
        //     return $this->redirectToRoute('app_login', ['tipo' => 'professor']);
        // }
    }


    private function obrigadoAction($senha, $email, $nome)
    {

        try {
            $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                ->setUsername($_ENV['MAILER_USERNAME'])
                ->setPassword($_ENV['MAILER_PASSWORD']);

            $mailer = new \Swift_Mailer($transport);
            $message = (new \Swift_Message('Quiz Class '))
                ->setSubject("Bem vindo ao Quiz Class")
                ->setFrom(['contato@quizclass.com.br' => 'QuizClass'])
                ->setTo([$email])
                ->setBody(
                    $this->render(
                        'emails/Home/emailSenha.html.twig',
                        [
                            'senha' => $senha,
                            'email' => $email
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash(
                'success',
                "Seja bem vindo ao Quiz Class! Você receberá um e-mail com sua senha de acesso ao Quiz Class.
                Caso tenha alguma dúvida, envie um e-mail para contato@quizclass.com.br. Comece criando o seu primeiro Quiz!"
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );

            return $this->redirectToRoute('app_login', ['tipo' => 'professor']);
        }
    }

    private function enviarLeadRd(RDStationService $rDStationService, $nome, $email)
    {
        $rDStationService->sendLeadRD($nome, $email, true, 'CADASTRO');
    }

    private function getCaptcha($secret)
    {
        $retorno = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lct_eAZAAAAAC5-Kmr751mM9k6BNfqw5WT0ox3t&response={$secret}");
        return json_decode($retorno);
    }

    private static function generateCode()
    {
        $agora = new \DateTime('now');
        return strtoupper(substr(md5($agora->format('Ymdhis')), 1, 6));
    }

    private function registrarPlanoMarketingFlex($type, User $user, PlanoAcessoService $planoAcessoService)
    {
        if (!empty($type) && $type === 'MARKETING_FLEX')
            $planoAcessoService->registrarPlanoMarketingFlex($user);
    }
}
