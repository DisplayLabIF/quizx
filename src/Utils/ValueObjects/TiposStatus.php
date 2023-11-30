<?php


namespace App\Utils\ValueObjects;


class TiposStatus
{

    private array $TiposStatusCompra = [
        'FINALIZADA' => 'FINALIZADA',
        'AGUARDANDO_CONFIRMACAO_PAGAMENTO' => 'AGUARDANDO_CONFIRMACAO_PAGAMENTO',
        'EM_ANDAMENTO' => 'EM_ANDAMENTO',
        'ASSINATURA_ATIVA' => 'ASSINATURA_ATIVA',
        'ASSINATURA_CANCELADA' => 'ASSINATURA_CANCELADA',
        'ASSINATURA_VENCIDA' => 'ASSINATURA_VENCIDA'
    ];
    private array $LabelTiposStatusCompra = [
        'FINALIZADA' => '<span class="text-success font-weight-bold">Finalizada</span>',
        'AGUARDANDO_CONFIRMACAO_PAGAMENTO' => '<span class="text-danger font-weight-bold">Aguardando confirmação de pagamento</span>',
        'EM_ANDAMENTO' => '<span class="text-secondary font-weight-bold">Em andamento</span>',
        'ASSINATURA_ATIVA' => '<span class="text-success font-weight-bold">Assinatura ativa</span>',
        'ASSINATURA_CANCELADA' => '<span class="text-danger font-weight-bold">Assinatura cancelada</span>',
        'ASSINATURA_VENCIDA' => '<span class="text-warning font-weight-bold">Assinatura vencida</span>'
    ];

    private array $TiposStatusParcela = [
        'FINALIZADA' => 'FINALIZADA',
        'AGUARDANDO_CONFIRMACAO_PAGAMENTO' => 'AGUARDANDO_CONFIRMACAO_PAGAMENTO',
        'ABERTO' => 'ABERTO',
        'EM_ANDAMENTO' => 'EM_ANDAMENTO',
        'CANCELADA' => 'CANCELADA',
        'ESTORNADO' => 'ESTORNADO',
        'BOLETO_NAO_GERADO' => 'BOLETO_NAO_GERADO'
    ];

    private array $LabelTiposStatusParcela = [
        'FINALIZADA' => '<span class="text-success font-weight-bold">Finalizada</span>',
        'AGUARDANDO_CONFIRMACAO_PAGAMENTO' => '<span class="text-danger font-weight-bold">Aguardando confirmação de pagamento</span>',
        'BOLETO_NAO_GERADO' => '<span class="text-secondary font-weight-bold">Boleto não gerado</span>',
        'ABERTO' => '<span class="text-secondary font-weight-bold">Aberta</span>',
        'EM_ANDAMENTO' => '<span class="text-secondary font-weight-bold">Em andamento</span>',
        'NAO_COMPENSADA' => '<span class="text-warning font-weight-bold">Pago - não compensada</span>',
        'CANCELADA' => '<span class="text-danger font-weight-bold">Cancelada</span>',
        'ESTORNADO' => '<span class="text-secondary font-weight-bold">Valor estornado</span>'
    ];

    private array $TiposStatusMatricula = [
        'PRE_MATRICULA' => 'PRE_MATRICULA',
        'FINALIZADA' => 'FINALIZADA',
        'CANCELADA' => 'CANCELADA'
    ];
    private array $LabelTiposStatusMatricula = [
        'PRE_MATRICULA' => '<span class="text-secondary font-weight-bold">Pré-matricula</span>',
        'FINALIZADA' => '<span class="text-success font-weight-bold">Finalizada</span>',
        'CANCELADA' => '<span class="text-danger font-weight-bold">Cancelada</span>'
    ];

    public function getTiposStatusCompra()
    {
        return $this->TiposStatusCompra;
    }
    public function getLabelTiposStatusCompra()
    {
        return $this->LabelTiposStatusCompra;
    }

    public function getTiposStatusParcela()
    {
        return $this->TiposStatusParcela;
    }
    public function getLabelTiposStatusParcela()
    {
        return $this->LabelTiposStatusParcela;
    }

    public function getTiposStatusMatricula()
    {
        return $this->TiposStatusMatricula;
    }
    public function getLabelTiposStatusMatricula()
    {
        return $this->LabelTiposStatusMatricula;
    }

    public function getTipoCompra()
    {
        return $this->TipoCompra;
    }
}
