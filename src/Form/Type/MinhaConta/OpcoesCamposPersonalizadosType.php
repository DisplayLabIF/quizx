<?php

namespace App\Form\Type\MinhaConta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Quiz\CamposPersonalizados;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OpcoesCamposPersonalizadosType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('opcoes', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => '',
                    'style' => 'width: 250px;'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
