<?php

namespace App\Form\Type\Quiz;

use App\Entity\Quiz\PersonalizacaoEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalizacaoEmailType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('logo', TextType::class, [
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('cor', ColorType::class, [
                'label' => 'Cor principal',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('urlBase', TextType::class, [
                'label' => 'Endereço de destino ao clicar no botão',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'ex.:https://quizclass.com.br'
                ],
                'required' => false,
            ])

            ->add('texto', TextareaType::class, [
                'label' => 'Texto do e-mail',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('textoBotao', TextType::class, [
                'label' => 'Nome do botão (CTA)',
                'attr' => [
                    'class' => 'form-default',
                    'maxLength' => "50"
                ],
                'required' => false,
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default',
                    'style' => 'width: 151px; font-weight: bold;'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonalizacaoEmail::class,
        ]);
    }
}
