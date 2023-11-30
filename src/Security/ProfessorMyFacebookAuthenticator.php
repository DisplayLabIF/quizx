<?php

namespace App\Security;

use App\Entity\User; // your user entity
use App\Entity\User\AdmEscola;
use App\Entity\User\Professor;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class ProfessorMyFacebookAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    private $clientRegistry;
    private $em;
    private $router;
    private $passwordEncoder;
    private $session;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
    }

    public function supports(Request $request)
    {
        if (strpos($request->get('type'), 'PLANO') !== false) {
            $session = $request->getSession();
            $session->set('tipo-plano', $request->get('type'));
        }

        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_facebook_check_professor';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);
        // var_dump($facebookUser); die;
        $email = $facebookUser->getEmail();

        // 1) have they logged in with Facebook before? Easy!
        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser) {
            if (!$existingUser->isActive()) {
                throw new CustomUserMessageAuthenticationException('Usuario desativado.');
            }

            $this->setLastAndQtdLogin($existingUser);

            return $existingUser;
        }

        // 2) do we have a matching user by email?
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($user) {
            // 3) Maybe you just want to "register" them by creating
            // a User object
            $user
                ->setFacebookId($facebookUser->getId())
                ->setImagemPerfil($facebookUser->getPictureUrl());
        } else {
            $user = new AdmEscola();

            $user
                ->setFacebookId($facebookUser->getId())
                ->setImagemPerfil($facebookUser->getPictureUrl())
                ->setNome($facebookUser->getName())
                ->setEmail($email)
                ->setPassword(null)
                ->setRoles(['ROLE_ADMIN']);
        }

        $tipoPlano = $this->session->get('tipo-plano');

        if ($tipoPlano)
            $user->setPlano($tipoPlano);

        $this->setLastAndQtdLogin($user);

        $this->em->persist($user);
        $this->em->flush();


        return $user;
    }

    /**
     * @return FacebookClient
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('facebook_main_professor');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $targetUrl = $this->router->generate('app_dashboard');

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    private function setLastAndQtdLogin(User $user)
    {
        $user
            ->setQtdLogin($user->getQtdLogin() + 1)
            ->setLastLogin(new \DateTime('now'));

        $this->em->persist($user);
        $this->em->flush();
    }
}
