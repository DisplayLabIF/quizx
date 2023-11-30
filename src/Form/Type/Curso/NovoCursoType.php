<?php

namespace App\Form\Type\Curso;

use App\Entity\Curso\Curso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NovoCursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'placeholder' => 'Nome do curso',
                    'class' => 'form-default'
                ],
                'required' => true
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default mt-2 pr-5 pl-5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Curso::class,
            'assunto' => ''
        ]);
    }
}
