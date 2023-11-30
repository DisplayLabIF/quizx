<?php


namespace App\Controller\Site;

use App\Entity\User;
use App\Entity\User\AdmEscola;
use App\Service\RDStationService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="site_home")
     */
    public function index(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser() && $this->getUser()->getTipo() === 'ALUNO')
            return $this->redirectToRoute('aluno_dashboard');

        $typeLogin = $request->get('tipo');
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Site/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }
}
