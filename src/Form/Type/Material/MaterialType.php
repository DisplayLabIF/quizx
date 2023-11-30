<?php

namespace App\Form\Type\Material;

use App\Entity\Curso\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome do material',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => true,
            ])
            ->add('tipo', ChoiceType::class, [
                'label' => 'Tipo do material',
                'choices'  => [
                    'Áudio' => 'audio',
                    'Vídeo' => 'video',
                    'Link Youtube' => 'link',
                    'Imagem' => 'image',
                    'Documento' => 'document',
                ],
                'attr' => [
                    'class' => 'form-default form-select tipo_arquivo',
                ],
                'required' => true,
            ])
            ->add('file', TextType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'mb-0',
                    'style' => 'width: 100%;'
                ],
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Ex: https://youtu.be/1nbhR7N3TYE'
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
        ]);
    }
}
