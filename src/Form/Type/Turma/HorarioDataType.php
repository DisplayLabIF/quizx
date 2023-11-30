<?php

namespace App\Form\Type\Turma;

use App\Entity\Curso\Aula;
use App\Entity\Curso\HorarioData;
use App\Repository\AulaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HorarioDataType extends AbstractType
{
    private $user;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->user = $options['user'];

        $builder
            ->add('dataAula', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'style' => 'height:100%;'
                ],
                'row_attr' => [
                    'class' => 'form-default',
                    'style' => 'width:100%;'
                ],
                'required' => true,
            ])
            ->add('aula', EntityType::class, [
                'label' => 'Aula',
                'class' => Aula::class,
                'query_builder' => function (AulaRepository $er) {
                    return $er->getAulasAoVivo($this->user);
                },
                'group_by' => 'modulo.nome',
                'attr' => [
                    'class' => 'form-default form-select',
                ],
                'choice_label' => 'nome',
                'placeholder' => 'Selecione uma aula',
                'required' => false,
            ]);
        // ->add('horaInicio', TimeType::class, [
        //     'input'  => 'datetime',
        //     'widget' => 'choice',
        // ])
        // ->add('horaTermino', TimeType::class, [
        //     'input'  => 'datetime',
        //     'widget' => 'choice',
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HorarioData::class,
            'user' => ''
        ]);
    }
}
