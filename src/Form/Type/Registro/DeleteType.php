<?php

namespace App\Form\Type\Registro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;

class DeleteType extends AbstractType
{
    private $codigo;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->codigo = $options['codigoConfirmacao'];
        $builder
            ->add('codigoConfirmacao', PasswordType::class, [
                'label' => 'Código de confirmação',
                'attr' => [
                    'placeholder' => 'Seu código',
                    'class' => 'form-default'
                ],
                'constraints' => [new EqualTo(['value' => $this->codigo, 'message' => 'Código inválido!'])],
                'mapped' => false
            ])
            ->add('deletar', SubmitType::class, [
                'label' => 'Deletar',
                'attr' => [
                    'class' => 'btn btn-block shadow-default btn-danger'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'codigoConfirmacao' => null
        ]);
    }
}
