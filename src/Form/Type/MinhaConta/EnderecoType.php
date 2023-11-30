<?php

namespace App\Form\Type\MinhaConta;

use App\Entity\Base\Contato;
use App\Entity\Base\Endereco;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnderecoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cep', TextType::class, [
                'label' => 'CEP',
                'attr' => [
                    'class' => 'form-default',
                    'data-js' => 'cep'
                ],
            ])
            ->add('logradouro', TextType::class, [
                'label' => 'Logradouro',
                'attr' => [
                    'class' => 'form-default'
                ],
            ])
            ->add('numero', IntegerType::class, [
                'label' => 'Número',
                'attr' => [
                    'class' => 'form-default'
                ],
            ])
            ->add('complemento', TextType::class, [
                'label' => 'Complemento',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false
            ])
            ->add('bairro', TextType::class, [
                'label' => 'bairro',
                'attr' => [
                    'class' => 'form-default'
                ],
            ])
            ->add('cidade', TextType::class, [
                'label' => 'Cidade',
                'attr' => [
                    'class' => 'form-default'
                ],
            ])
            ->add('estado', TextType::class, [
                'label' => 'Estado',
                'attr' => [
                    'class' => 'form-default',
                    'maxlength' => "2"
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Endereco::class,
        ]);
    }
}
