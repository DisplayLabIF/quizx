<?php

namespace App\Form\Type\MinhaConta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class DadosAcessoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'Seu nome',
                    'class' => 'form-default',
                    'style' => 'cursor: not-allowed;',
                    'readonly' => true
                ],
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'As senhas devem ser iguais.',
                'options' => ['attr' => ['class' => 'password-field form-default']],
                'required' => false,
                'first_options'  => ['label' => 'Nova senha'],
                'second_options' => ['label' => 'Repita a senha'],
                'required' => false,
                'mapped' => false
            ])
            ->add('alterarDados', SubmitType::class, [
                'label' => 'Alterar dados',
                'attr' => [
                    'class' => 'btn shadow-default btn-default'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
