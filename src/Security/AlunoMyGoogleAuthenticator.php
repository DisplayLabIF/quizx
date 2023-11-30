<?php

namespace App\Security;

use App\Entity\User; // your user entity
use App\Entity\User\Aluno;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AlunoMyGoogleAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    private $clientRegistry;
    private $em;
    private $router;
    private $passwordEncoder;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check_aluno';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);
        // var_dump($googleUser); die;
        $email = $googleUser->getEmail();

        // 1) have they logged in with google before? Easy!
        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['googleId' => $googleUser->getId()]);
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
                ->setGoogleId($googleUser->getId())
                ->setImagemPerfil(null);
            $this->em->persist($user);
            $this->em->flush();
        } else {
            $user = new Aluno();

            $user
                ->setGoogleId($googleUser->getId())
                ->setImagemPerfil(null)
                ->setNome($googleUser->getName())
                ->setEmail($email)
                ->setPassword(null)
                ->setRoles(['ROLE_ALUNO']);

            $this->em->persist($user);
            $this->em->flush();
        }

        $this->setLastAndQtdLogin($user);

        return $user;
    }

    /**
     * @return GoogleClient
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry
            // "google" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('google_aluno');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $targetUrl = $this->router->generate('aluno_dashboard');

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
