<?php

namespace App\Form\Type\Turma;

use App\Entity\Curso\Turma;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TurmaType extends AbstractType
{
    private $user;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $this->user = $options['user'];

        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => true,
            ])
            ->add('dataInicio', DateType::class, [
                'label' => 'Data início',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => false,
            ])
            ->add('dataTermino', DateType::class, [
                'label' => 'Data término',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-default'
                ],
                'label_attr' => [
                    'class' => 'd-flex flex-row text-nowrap padding-span',
                ],
                'constraints' => [
                    new Callback(function ($object, ExecutionContextInterface $context) {
                        $dataInicio = $context->getRoot()->getData()->getDataInicio();
                        $dataTermino = $object;
                        if (is_a($dataInicio, \DateTime::class) && is_a($dataTermino, \DateTime::class)) {
                            if ($dataTermino->format('U') - $dataInicio->format('U') <= 0) {
                                $context
                                    ->buildViolation('Data de término deve ser depois da data de início')
                                    ->addViolation();
                            }
                        }
                    }),
                ],
                'required' => false,
            ])
            ->add('limiteAlunos', IntegerType::class, [
                'label' => 'Limite de alunos',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => true,
            ])
            ->add('observacao', TextareaType::class, [
                'label' => 'Observação da turma',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false,
            ])
            
            ->add('horarios', CollectionType::class, [
                'label' => false,
                'entry_type' => HorarioType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('horarioDatas', CollectionType::class, [
                'label' => false,
                'entry_type' => HorarioDataType::class,
                'entry_options' => ['label' => false, 'user' => $this->user],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default pr-5 pl-5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Turma::class,
            'user' => ''
        ]);
    }
}
