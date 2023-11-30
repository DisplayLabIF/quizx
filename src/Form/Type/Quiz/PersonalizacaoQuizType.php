<?php

namespace App\Form\Type\Quiz;

use App\Entity\Quiz\PersonalizacaoQuiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalizacaoQuizType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('defaultColor', ColorType::class, [
                'label' => 'Cor padrão',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('primaryColor', ColorType::class, [
                'label' => 'Cor primaria',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('secondaryColor', ColorType::class, [
                'label' => 'Cor secundaria',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('textPrimaryColor', ColorType::class, [
                'label' => 'Cor de texto primário apresentação',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('textSecondaryColor', ColorType::class, [
                'label' => 'Cor de texto secundário apresentação',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('backgroundImageApresentacao', TextType::class, [
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('backgroundColorApresentacao', ColorType::class, [
                'label' => 'Cor background apresentação',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('textPrimaryColorQuestao', ColorType::class, [
                'label' => 'Cor de texto primário questão',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('textSecondaryColorQuestao', ColorType::class, [
                'label' => 'Cor de texto secundário questão',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('backgroundImageQuestao', TextType::class, [
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('backgroundColorQuestao', ColorType::class, [
                'label' => 'cor background questão',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('backgroundSizeApresentacao', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input d-flex flex-row'
                ],
                'choices' => [
                    'Contain' => 'contain',
                    'Cover' => 'cover',
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('backgroundSizeQuestao', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input d-flex flex-row'
                ],
                'choices' => [
                    'Contain' => 'contain',
                    'Cover' => 'cover',
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default mt-3',
                    'style' => 'width: 151px; font-weight: bold;'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonalizacaoQuiz::class,
        ]);
    }
}
