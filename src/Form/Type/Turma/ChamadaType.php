<?php

namespace App\Form\Type\Turma;

use App\Entity\Curso\HorarioData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChamadaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('aulaPresencas', CollectionType::class, [
                'label' => false,
                'entry_type' => ChamadaAulaPresencaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('finalizarChamada', SubmitType::class, [
                'label' => 'Finalizar chamada',
                'attr' => [
                    'class' => 'btn shadow-default btn-default'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HorarioData::class,
        ]);
    }
}
