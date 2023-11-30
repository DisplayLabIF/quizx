<?php

namespace App\Service;

use App\Entity\Admin\Compra;
use App\Entity\Admin\PlanoAcesso;
use App\Entity\Admin\Recurso;
use App\Entity\User;
use App\Repository\CompraRepository;
use App\Repository\ParcelaRepository;
use App\Repository\PlanoAcessoRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlanoAcessoService
{
    private $entityManager;
    private $planoAcessoRepository;
    private $compraRepository;
    private $tempoDeTeste;
    private $parcelaRepository;

    public function __construct(EntityManagerInterface $entityManager, PlanoAcessoRepository $planoAcessoRepository, CompraRepository $compraRepository, ParcelaRepository $parcelaRepository)
    {
        $this->entityManager = $entityManager;
        $this->planoAcessoRepository = $planoAcessoRepository;
        $this->compraRepository = $compraRepository;
        $this->tempoDeTeste = 30;
        $this->parcelaRepository = $parcelaRepository;
    }

    public function getPlanoAcessoAtivo($userId): PlanoAcesso
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->planoAcessoRepository->findOneBy(['nome' => 'FREE']);
        }

        $compra = $this->compraRepository->getAssinaturaPlanoAtivo($user->getId());

        if ($compra) {
            // $ultimaParcelaPaga = $this->parcelaRepository->getUltimaParcelaPaga($compra->getId());

            // if ($this->assinaturaValida($ultimaParcelaPaga->getDataPagamento())) {
            //     return $compra->getPlano();
            // }

            if ($this->assinaturaValida($compra->getValidadeAssinatura())) {
                return $compra->getPlano();
            }
        }

        if ($user->getPlano() !== 'PLANO_FREE' && $this->verificarValidadeTesteGratuito($user->getCreated())) {

            $plano = $this->planoAcessoRepository->findOneBy([
                'nome' => str_replace('PLANO_', '', $user->getPlano())
            ]);

            if ($plano) return $plano;
        }

        return $this->planoAcessoRepository->findOneBy(['nome' => 'FREE']);
    }

    public function verificarPlanoPossuiRecurso(PlanoAcesso $plano, $nomeRecurso)
    {
        $recurso = $this->entityManager->getRepository(Recurso::class)
            ->findOneBy(['planoAcesso' => $plano->getId(), 'nome' => $nomeRecurso]);

        return $recurso;
    }

    private function verificarValidadeTesteGratuito(\DateTime $created)
    {

        $dataVencimentoTeste = $created->modify('+' . $this->tempoDeTeste . ' days');
        $dateNow = new \DateTime('now');

        if ($dataVencimentoTeste >= $dateNow)
            return true;

        return false;
    }

    public function assinaturaValida($dataVencimentoAssinatura)
    {
        // $diasAssinaturaMensal = 30;

        // $dataVencimentoAssinatura = $dataPagamento->modify('+' . $diasAssinaturaMensal . ' days');
        $dateNow = new \DateTime('now');

        if ($dataVencimentoAssinatura >= $dateNow)
            return true;

        return false;
    }
}
