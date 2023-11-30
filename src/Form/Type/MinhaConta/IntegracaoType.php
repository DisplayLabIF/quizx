<?php

namespace App\Form\Type\MinhaConta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Escola;

class IntegracaoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('banco', TextType::class, [
                'label' => 'Banco',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('agencia', TextType::class, [
                'label' => 'Agência',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('conta', TextType::class, [
                'label' => 'Conta',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('braspagMerchantId', TextType::class, [
                'label' => 'Braspag MerchantId',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('rdStationApiToken', TextType::class, [
                'label' => 'RD Station: api token público',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('rdStationEventoConversao', TextType::class, [
                'label' => 'RD Station: nome do evento de conversão',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('rdStationClientSecret', TextType::class, [
                'label' => 'RD Station: client secret',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('rdStationClientId', TextType::class, [
                'label' => 'RD Station: client ID',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default pl-5 pr-5 mb-5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Escola::class
        ]);
    }
}
