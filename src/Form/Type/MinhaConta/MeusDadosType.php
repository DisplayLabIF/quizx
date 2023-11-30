<?php

namespace App\Form\Type\MinhaConta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use App\Form\Type\MinhaConta\ContatoType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class MeusDadosType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'placeholder' => 'Seu nome',
                    'class' => 'form-default'
                ],
                'required' => true
            ])
            ->add('cpf', TextType::class, [
                'label' => 'CPF',
                'attr' => [
                    'placeholder' => '',
                    'class' => 'form-default',
                    'data-js' => 'cpf',
                ],
                'required' => true
            ])
            ->add('contato', ContatoType::class, [
                'label' => false
            ])
            ->add('enderecos', CollectionType::class, [
                'label' => false,
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'entry_type' => EnderecoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'required' => true

            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default pl-5 pr-5 mb-5'
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
