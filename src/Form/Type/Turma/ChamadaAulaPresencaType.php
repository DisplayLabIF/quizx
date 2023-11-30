<?php

namespace App\Form\Type\Turma;

use App\Entity\Curso\AulaPresenca;
use App\Entity\Curso\Matricula;
use App\Entity\Curso\Turma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChamadaAulaPresencaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('presente', CheckboxType::class, [
                'label'    => 'Presente',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AulaPresenca::class,
        ]);
    }
}
