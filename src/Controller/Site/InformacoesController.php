<?php


namespace App\Controller\Site;

use App\Entity\User;
use App\Form\Type\Informacoes\FaleConoscoType;
use App\Form\Type\Registro\DeleteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class InformacoesController extends AbstractController
{
    /**
     * @Route("/sobre",
     *     name="site_sobre")
     */
    public function sobre(Request $request)
    {
        return $this->render('Site/informacoes/sobre.html.twig');
    }

    /**
     * @Route("/politica-privacidade",
     *     name="site_politica_privacidade")
     */
    public function politica(Request $request)
    {
        return $this->render('Site/informacoes/politica.html.twig');
    }

    /**
     * @Route("/termos-de-uso",
     *     name="site_termos_uso")
     */
    public function termosUso(Request $request)
    {
        return $this->render('Site/informacoes/termos-uso.html.twig');
    }

    /**
     * @Route("/fale-conosco",
     *     name="site_fale_conosco")
     */
    public function faleConosco(Request $request)
    {
        $form = $this->createForm(FaleConoscoType::class, null, ['assunto' => 'Fale conosco']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                    ->setUsername($_ENV['MAILER_USERNAME'])
                    ->setPassword($_ENV['MAILER_PASSWORD']);

                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Quiz Class '))
                    ->setSubject($form->get('assunto')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo(['contato@quizclass.com.br', 'ewerton.code@gmail.com'])
                    ->setBody(
                        $this->render(
                            'emails/faleConosco.html.twig',
                            [
                                'nome' => $form->get('nome')->getData(),
                                'email' => $form->get('email')->getData(),
                                'telefone' => $form->get('telefone')->getData(),
                                'departamento' => $form->get('departamento')->getData(),
                                'assunto' => $form->get('assunto')->getData(),
                                'mensagem' => $form->get('mensagem')->getData()
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                $this->addFlash(
                    'success',
                    'Sua mensagem foi enviada!'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    'ERRO, TENTE NOVAMENTE.'
                );
            }

            return $this->redirectToRoute('site_fale_conosco');
        }

        return $this->render('Site/informacoes/fale-conosco.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trabalhe-conosco",
     *     name="site_trabalhe_conosco")
     */
    public function trabalheConosco(Request $request)
    {
        $form = $this->createForm(FaleConoscoType::class, null, ['assunto' => 'Trabalhe conosco']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                    ->setUsername($_ENV['MAILER_USERNAME'])
                    ->setPassword($_ENV['MAILER_PASSWORD']);

                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Quiz Class '))
                    ->setSubject($form->get('assunto')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo(['contato@quizclass.com.br', 'ewerton.code@gmail.com'])
                    ->setBody(
                        $this->render(
                            'emails/faleConosco.html.twig',
                            [
                                'nome' => $form->get('nome')->getData(),
                                'email' => $form->get('email')->getData(),
                                'telefone' => $form->get('telefone')->getData(),
                                'departamento' => $form->get('departamento')->getData(),
                                'assunto' => $form->get('assunto')->getData(),
                                'mensagem' => $form->get('mensagem')->getData()
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                $this->addFlash(
                    'success',
                    'Sua mensagem foi enviada!'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    'ERRO, TENTE NOVAMENTE.'
                );
            }

            return $this->redirectToRoute('site_trabalhe_conosco');
        }

        return $this->render('Site/informacoes/trabalhe-conosco.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/suporte",
     *     name="site_suporte")
     */
    public function suporte(Request $request)
    {
        $form = $this->createForm(FaleConoscoType::class, null, ['assunto' => 'Suporte']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                    ->setUsername($_ENV['MAILER_USERNAME'])
                    ->setPassword($_ENV['MAILER_PASSWORD']);

                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Quiz Class '))
                    ->setSubject($form->get('assunto')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo(['contato@quizclass.com.br', 'ewerton.code@gmail.com'])
                    ->setBody(
                        $this->render(
                            'emails/faleConosco.html.twig',
                            [
                                'nome' => $form->get('nome')->getData(),
                                'email' => $form->get('email')->getData(),
                                'telefone' => $form->get('telefone')->getData(),
                                'departamento' => $form->get('departamento')->getData(),
                                'assunto' => $form->get('assunto')->getData(),
                                'mensagem' => $form->get('mensagem')->getData()
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                $this->addFlash(
                    'success',
                    'Sua mensagem foi enviada!'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    'ERRO, TENTE NOVAMENTE.'
                );
            }

            return $this->redirectToRoute('site_suporte');
        }

        return $this->render('Site/informacoes/suporte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/venda",
     *     name="site_venda")
     */
    public function venda(Request $request)
    {
        $form = $this->createForm(FaleConoscoType::class, null, ['assunto' => 'Venda']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                    ->setUsername($_ENV['MAILER_USERNAME'])
                    ->setPassword($_ENV['MAILER_PASSWORD']);

                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Quiz Class '))
                    ->setSubject($form->get('assunto')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo(['contato@quizclass.com.br', 'ewerton.code@gmail.com'])
                    ->setBody(
                        $this->render(
                            'emails/faleConosco.html.twig',
                            [
                                'nome' => $form->get('nome')->getData(),
                                'email' => $form->get('email')->getData(),
                                'telefone' => $form->get('telefone')->getData(),
                                'departamento' => $form->get('departamento')->getData(),
                                'assunto' => $form->get('assunto')->getData(),
                                'mensagem' => $form->get('mensagem')->getData()
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                $this->addFlash(
                    'success',
                    'Sua mensagem foi enviada!'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    'ERRO, TENTE NOVAMENTE.'
                );
            }

            return $this->redirectToRoute('site_venda');
        }

        return $this->render('Site/informacoes/venda.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deletion",
     *     name="site_deletion")
     */
    public function deletarDados(Request $request)
    {
        $user_id = $request->query->get('id');
        $user = null;
        if ($user_id) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($user_id);

            $form = $this->createForm(DeleteType::class, $user, ['codigoConfirmacao' => $user->getFacebookId()]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->exclusaoLogicaUsuario($user);

                return $this->redirectToRoute('site_home');
            }

            return $this->render('Site/informacoes/deletion.html.twig', [
                'form' => $form->createView(),
                'user' => $user
            ]);
        }

        return $this->render('Site/informacoes/deletion.html.twig', [
            'user' => $user
        ]);
    }

    public function exclusaoLogicaUsuario($user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($user->getTipo() === 'PROFESSOR') {
            foreach ($user->getCursos() as $curso) {
                foreach ($curso->getTurmas() as $turma) {
                    $turma->setActive(false);
                }
                $curso->setActive(false);
            }
            $user->getEscola()->setActive(false);
        } else if ($user->getTipo() === 'ALUNO') {
            $user->getContato()->setActive(false);
            foreach ($user->getEnderecos() as $endereco) {
                $endereco->setActive(false);
            }
        }

        $user->setActive(false);
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
