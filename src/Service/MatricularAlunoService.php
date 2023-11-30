<?php

namespace App\Service;

use App\Entity\Base\Contato;
use App\Entity\Base\Endereco;
use App\Entity\Admin\Compra;
use App\Entity\Curso\Matricula;
use App\Entity\Curso\Turma;
use App\Entity\User;
use App\Entity\User\Aluno;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MatricularAlunoService
{
    private $entityManager;
    private $passwordEncoder;


    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function efetuarMatricula($matricula, Turma $turma, $matriculaId = null)
    {
        $this->entityManager->beginTransaction();
        try {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $matricula['email']]);
            if (!$user) {
                $user = new Aluno();
                $user
                    ->setNome($matricula['nome'])
                    ->setEmail($matricula['email'])
                    ->setRoles(['ROLE_ALUNO']);
            } else {
                if ($user->getTipo() !== 'ALUNO') {
                    return  ['status' => 403];
                }
                if (!$matriculaId) {
                    foreach ($user->getMatriculas() as $matriculaUser) {
                        if ($turma->getMatriculas()->contains($matriculaUser)) {
                            return  ['status' => 422];
                        }
                    }
                }
            }

            if ($matriculaId) {
                $editMatricula = $this->entityManager->getRepository(Matricula::class)->find($matriculaId);

                $editMatricula
                    ->setStatus(($matricula['status']) ?? 'FINALIZADA')
                    ->setIsAluno(true);

                $contato = $editMatricula->getUser()->getContato();
                $contato->setCelular($matricula['telefone']);

            
                $this->entityManager->persist($editMatricula);
                $this->entityManager->persist($contato);

                $this->entityManager->flush();
                $this->entityManager->commit();

                return  [
                    'status' => 200,
                    'matriculaId' => $editMatricula->getId()
                ];
            } else {
                $contato = new Contato();
                $contato->setCelular($matricula['telefone']);


                $user
                    ->setPassword($this->passwordEncoder->encodePassword(
                        $user,
                        $matricula['senha']
                    ))
                    ->setContato($contato);

               
                $tiposStatus = new \App\Utils\ValueObjects\TiposStatus();
                $tiposStatus = $tiposStatus->getTiposStatusMatricula();


                $newMatricula = new Matricula();
                $newMatricula
                    ->setUser($user)
                    ->setData(new \DateTime('now'))
                    ->setTurma($turma)
                    ->setStatus('FINALIZADA')
                    ->setRand(rand(100001, 999999))
                    ->setIsAluno(true);
                $this->entityManager->persist($newMatricula);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->entityManager->commit();
                return  [
                    'status' => 200,
                    'matriculaId' => $newMatricula->getId()
                ];
            }
        } catch (Exception $e) {
            $this->entityManager->rollBack();
            throw $e;
        }
    }

}
