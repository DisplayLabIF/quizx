<?php

namespace App\Form\Type\Jogo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdentificarAlunoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'placeholder' => 'Seu nome',
                    'class' => 'form-default',
                    'maxLength' => "15"
                ],
                'required' => true,
            ])
            ->add('confirmar', SubmitType::class, [
                'label' => 'Ir para o jogo',
                'attr' => [
                    'class' => 'btn btn-default shadow-default mt-3 pl-5 pr-5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
