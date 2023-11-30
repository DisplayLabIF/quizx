<?php

namespace App\Form\Type\Turma;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatriculaType extends AbstractType
{
    private $editarMatricula;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->editarMatricula = $options['editar_matricula'];

        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Seu nome'
                ],
                'required' => true,
                'disabled' =>  $this->editarMatricula ? true : false,

            ])
            
            ->add('telefone', TextType::class, [
                'label' => 'Telefone',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => '(99) 9 9999-9999',
                    'data-js' => 'phone'
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'E-mail para contato'
                ],
                'required' => true,
                'disabled' =>  $this->editarMatricula ? true : false,

            ])
            ->add('senha', PasswordType::class, [
                'label' => 'Senha de acesso ao curso',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Sua senha',
                ],
                'disabled' =>  $this->editarMatricula ? true : false,
                'required' => $this->editarMatricula ? false : true,
            ])
            
            ->add('status', ChoiceType::class, [
                'label' => $this->editarMatricula ? 'Status da matricula' : false,
                'choices'  => [
                    'Escolha uma opção' => null,
                    'Pré-matricula' => 'PRE_MATRICULA',
                    'Finalizada' => 'FINALIZADA',
                    'Cancelada' => 'CANCELADA'
                ],
                'choice_attr' => [
                    'Escolha uma opção' => ['disabled' => true],
                ],
                'placeholder' => false,
                'attr' => [
                    'class' => 'form-default form-select',
                    'style' => $this->editarMatricula ? '' : 'display: none;'
                ],
                // 'constraints' => [new NotNull(['message' => 'Escolha uma opção!'])],
                'required' => $this->editarMatricula ? true : false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'label_check_aluno' => null,
            'editar_matricula' => false
        ]);
    }
}
