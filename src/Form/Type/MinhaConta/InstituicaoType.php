<?php

namespace App\Form\Type\MinhaConta;

use App\Entity\Curso\Curso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Escola;
use App\Repository\CursoRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class InstituicaoType extends AbstractType
{
    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->user = $options['user'];

        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Nome da sua escola ou instituição ou página pessoal'
                ],
                'required' => true,
            ])
            ->add('url', TextType::class, [
                'label' => 'Endereço de acesso à sua página',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'cursos.quizclass.com.br',
                    'readonly' => true
                ],
                'required' => true,
            ])
            ->add('cnpj', TextType::class, [
                'label' => 'CNPJ/CPF',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'CNPJ da instituição'
                ],
                'required' => true,
            ])
            ->add('cursosDisponiveisVenda', EntityType::class, [
                'class' => Curso::class,
                'query_builder' => function (CursoRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->setParameter('user', $this->user);
                },
                'choice_label' => 'nome',
                'label' => 'Cursos que quero disponibilizar para venda',
                'placeholder' => 'Selecione os cursos',
                'multiple' => true,
                'attr' => [
                    'class' => 'form-default js-select2 form-select',
                ],
                'required' => false,
                'by_reference' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail para contato dos seus clientes',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'e-mail para contato'
                ],
                'required' => true
            ])
            ->add('descricao', CKEditorType::class, [
                'config' => [
                    'required' => false,
                    'inline' => true,
                ],
                'label' => 'Descrição sobre a sua instituição',

            ])
            ->add('imagem', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
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
            'data_class' => Escola::class,
            'user' => null
        ]);
    }
}
