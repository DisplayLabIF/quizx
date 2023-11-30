<?php

namespace App\Form\Type\Quiz;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ScriptExternoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('posicao', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-checkbox-input'
                ],
                'placeholder' => false,
                'choices' => [
                    'Adicionar na tag <head>' => "HEAD",
                    'Adicionar no inicio da tag <body>' => "BODY_INICIO",
                    'Adicionar no final da tag <body>' => "BODY_FIM",
                ],
                'data' => 'HEAD',
                'required' => false,
                'expanded' => true,
            ])
            ->add('script', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Cole aqui seu script'
                ],
                'row_attr' => [
                    'style' => 'width: 100%;'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
