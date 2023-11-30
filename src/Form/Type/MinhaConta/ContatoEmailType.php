<?php

namespace App\Form\Type\MinhaConta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Escola;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContatoEmailType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('remetente', EmailType::class, [
                'label' => 'E-mail remetente',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'e-mail para usar como remetente'
                ],
                'required' => false
            ])
            ->add('endereco', TextAreaType::class, [
                'label' => 'Endereço',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default pl-5 pr-5 mb-5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Escola::class
        ]);
    }
}
