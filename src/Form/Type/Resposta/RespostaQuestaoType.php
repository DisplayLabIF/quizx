<?php

namespace App\Form\Type\Resposta;

use App\Entity\Quiz\RespostaQuizQuestoes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class RespostaQuestaoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('correto', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-checkbox-input d-flex flex-row'
                ],
                'placeholder' => false,
                'choices' => [
                    'Certo' => true,
                    'Errado' => false,
                ],
                'required' => true,
                'expanded' => true,
            ])
            ->add('nota', NumberType::class, [
                'label' => 'Nota',
                'attr' => [
                    'class' => 'form-default'
                ],
                'input' => 'number',
                'html5' => true,
                'scale' => 2,
                'constraints' => [new PositiveOrZero(['message' => 'Valor invalido!'])],
                'required' => true,
            ])
            ->add('observacao', TextareaType::class, [
                'label' => 'Observação',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Caso necessário, insira aqui suas observações.'
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RespostaQuizQuestoes::class
        ]);
    }
}
