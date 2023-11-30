<?php

namespace App\Form\Type\MinhaConta;

use App\Entity\Base\Contato;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContatoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('celular', TextType::class, [
                'label' => 'Número de celular',
                'attr' => [
                    'placeholder' => 'Seu celular',
                    'class' => 'form-default',
                    'data-js' => 'phone'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contato::class,
        ]);
    }
}
