<?php

namespace App\Form\Type\Turma;

use App\Entity\Curso\Horario;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as TypeDateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HorarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dia', ChoiceType::class, [
                'label' => 'Dia da semana',
                'choices'  => [
                    'segunda-feira' => 'Mon',
                    'terça-feira' => 'Tue',
                    'quarta-feira' => 'Wed',
                    'quinta-feira' => 'Thu',
                    'sexta-feira' => 'Fri'
                ],
                'attr' => [
                    'class' => 'form-default form-select'
                ],
                'label_attr' => [
                    'class' => 'font-weight-normal',
                ],
                'row_attr' => [
                    'style' => 'padding: 0;'
                ],
                'required' => true,
            ])
            ->add('horaInicio', TimeType::class, [
                'label' => 'Hora de início',

                'label_attr' => [
                    'style' => 'padding: 0;margin-bottom: 3.2px;'
                ],
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('horaTermino', TimeType::class, [
                'label' => 'Hora de Término',

                'label_attr' => [
                    'style' => 'padding: 0; margin-bottom: 3.2px;'
                ],
                'input'  => 'datetime',
                'widget' => 'choice',
            ]);
        // ->add('horarioDatas', CollectionType::class, [
        //     'label' => false,
        //     'entry_type' => HorarioDataType::class,
        //     'entry_options' => ['label' => false],
        //     'allow_add' => true,
        //     'by_reference' => false,
        //     'allow_delete' => true,
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Horario::class,
        ]);
    }
}
