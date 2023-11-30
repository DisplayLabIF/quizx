<?php

namespace App\Form\Type\Registro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class CadastroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Seu nome',
                    'autofocus' => true,
                    'class' => 'form-default m-2'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Seu e-mail',
                    'class' => 'form-default m-2'
                ],
            ])
            ->add('contato', ContatoType::class)
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Sua senha',
                    'class' => 'form-default m-2'
                ],
            ])
            ->add('tipoUsuario', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                    'Professor' => 'PROFESSOR',
                ],
                'choice_attr' => [
                    'Eu sou' => ['disabled' => true],
                ],
                'placeholder' => false,
                'attr' => [
                    'class' => 'form-default form-select m-2'
                ],
                // 'constraints' => [new NotNull(['message' => 'Selecione um tipo de usuário!'])],
                'required' => false,
            ])
            ->add('privacy_policy', HiddenType::class, [
                'attr' => [
                    'data-privacy' => true,
                    'value' => 1
                ],
                'mapped' => false
            ])
            ->add('terms_of_use', HiddenType::class, [
                'attr' => [
                    'data-privacy' => true,
                    'value' => 1
                ],
                'mapped' => false
            ])
            ->add('cadastrar_gratis', SubmitType::class, [
                'label' => 'Cadastrar',
                'attr' => [
                    'class' => 'btn btn-block shadow-default btn-default m-2'
                ],
            ])

            // ->add('token', HiddenType::class, [
            //     'mapped' => false,
            //     'data' => 'abcdef',
            // ])
        ;
    }
}
