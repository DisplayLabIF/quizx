<?php

namespace App\Form\Type\Informacoes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class FaleConoscoType extends AbstractType
{
    private $assunto;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->assunto = $options['assunto'];

        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'placeholder' => 'Seu nome',
                    'class' => 'form-default'
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'Seu e-mail',
                    'class' => 'form-default'
                ],
                'required' => true,
            ])
            ->add('telefone', TextType::class, [
                'label' => 'Telefone',
                'attr' => [
                    'placeholder' => '(99) 9 9999-9999',
                    'data-js' => 'telefone',
                    'class' => 'form-default telefone'
                ],
                'required' => true,
            ])
            ->add('departamento', ChoiceType::class, [
                'label' => 'Departamento',
                'choices'  => [
                    'Selecione uma opção' => null,
                    'Financeiro' => 'FINANCEIRO',
                    'Suporte' => 'SUPORTE',
                    'Vendas' => 'VENDAS',
                    'Recursos Humanos' => 'RH',
                    'Outro' => 'outro'
                ],
                'choice_attr' => [
                    'Selecione uma opção' => ['disabled' => true],
                ],
                'placeholder' => false,
                'attr' => [
                    'class' => 'form-default form-select'
                ],
                'constraints' => [new NotNull(['message' => 'Selecione uma opção!'])],
                'required' => true,
            ])
            ->add('assunto', TextType::class, [
                'label' => 'Assunto',
                'attr' => [
                    'placeholder' => 'Informe o assunto',
                    'class' => 'form-default',
                    'value' => $this->assunto
                ],
                'required' => true,
            ])
            ->add('mensagem', TextareaType::class, [
                'label' => 'Mensagem',
                'attr' => [
                    'placeholder' => 'Insira sua mensagem',
                    'class' => 'form-default',
                    'rows' => 3
                ],
                'required' => true,
            ])
            ->add('enviar', SubmitType::class, [
                'label' => 'ENVIAR',
                'attr' => [
                    'class' => 'btn btn-default shadow-default mt-3 pl-5 pr-5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'assunto' => ''
        ]);
    }
}
