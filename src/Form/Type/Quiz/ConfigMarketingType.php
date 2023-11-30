<?php

namespace App\Form\Type\Quiz;

use App\Entity\Quiz\ConfiguracaoMarketingQuiz;
use App\Entity\Quiz\PersonalizacaoEmail;
use App\Form\Type\Quiz\ScriptExternoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigMarketingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título da página',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('image', TextType::class, [
                'label' => 'Imagem para Redes Sociais (meta)',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('descricao', TextareaType::class, [
                'label' => 'Meta descrição',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            ->add('scriptExternos', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => ScriptExternoType::class,
                'entry_options' => [
                    'row_attr' => [
                        'style' => 'width: 100%;'
                    ],
                ],
                'label' => false,
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default mt-3',
                    'style' => 'width: 151px; font-weight: bold;'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConfiguracaoMarketingQuiz::class,
        ]);
    }
}
