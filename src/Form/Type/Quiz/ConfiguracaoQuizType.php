<?php

namespace App\Form\Type\Quiz;

use App\Entity\Quiz\CamposPersonalizados;
use App\Entity\Quiz\ConfiguracaoQuiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use App\Form\Type\Material\MaterialType;
use App\Repository\CamposPersonalizadosRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ConfiguracaoQuizType extends AbstractType
{
    private $quizPontuacao;
    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->quizPontuacao = $options['quizPontuacao'];
        $this->user = $options['user'];

        $builder
            ->add('obterDadosRespondente', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input'
                ],
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('obterNome', CheckboxType::class, [
                'label' => 'Nome',
                'label_attr' => [
                    'class' => 'quais-dados-obter'
                ],
                'row_attr' => [
                    'class' => 'radio-checkbox-input',
                ],
                'required' => false,
            ])
            ->add('obterEmail', CheckboxType::class, [
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => 'quais-dados-obter'
                ],
                'row_attr' => [
                    'class' => 'radio-checkbox-input',
                ],
                'required' => false,
            ])
            ->add('obterTelefone', CheckboxType::class, [
                'label' => 'Telefone',
                'label_attr' => [
                    'class' => 'quais-dados-obter'
                ],
                'row_attr' => [
                    'class' => 'radio-checkbox-input',
                ],
                'required' => false,
            ])
            
            ->add('obterCpf', CheckboxType::class, [
                'label' => 'CPF',
                'label_attr' => [
                    'class' => 'quais-dados-obter'
                ],
                'row_attr' => [
                    'class' => 'radio-checkbox-input',
                ],
                'required' => false,
            ])
        
            ->add('obterCidade', CheckboxType::class, [
                'label' => 'Cidade',
                'label_attr' => [
                    'class' => 'quais-dados-obter'
                ],
                'row_attr' => [
                    'class' => 'radio-checkbox-input',
                ],
                'required' => false,
            ])
            ->add('quandoObterDados', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-checkbox-input'
                ],
                'placeholder' => false,
                'choices' => [
                    'No fim do quiz, como condição para que seja mostrada a quantidade de acertos ou nota.' => "FIM",
                    'No começo do teste, antes de começar a responder às questões.' => "INICIO",
                ],
                'required' => false,
                'expanded' => true,
            ])
            ->add('mostrarNota', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input flex-wrap'
                ],
                'choices' => [
                    'Sim' => 'MOSTRAR',
                    'Não mostrar resultado' => 'NAO_MOSTRAR'
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('definirTempoResposta', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input'
                ],
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('tempo', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-default',
                    'style' => 'width: 130px;'
                ],
                'constraints' => [new Positive(['message' => 'Valor invalido!'])],
                'required' => false,
            ])
            ->add('podePularQuestao', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input'
                ],
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            // ->add('mostraAleatoria', ChoiceType::class, [
            //     'label' => false,
            //     'attr' => [
            //         'class' => 'radio-button-row radio-checkbox-input'
            //     ],
            //     'choices' => [
            //         'Sim' => true,
            //         'Não' => false,
            //     ],
            //     'required' => true,
            //     'expanded' => true,
            // ])
            ->add('mostrarCorrecao', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input'
                ],
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            // ->add('configurarLandPage', ChoiceType::class, [
            //     'label' => false,
            //     'attr' => [
            //         'class' => 'radio-button-row radio-checkbox-input'
            //     ],
            //     'choices' => [
            //         'Sim' => true,
            //         'Não' => false,
            //     ],
            //     'empty_data' => '',
            //     'placeholder' => false,
            //     'required' => false,
            //     'expanded' => true,
            // ])
            ->add('mostrarGabarito', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input'
                ],
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('definirNotaMinima', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input',
                    'style' => $this->quizPontuacao > 0 ? '' : 'display: none;'
                ],
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('notaMinima', NumberType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-default',
                    'style' => $this->quizPontuacao > 0 ? 'width: 130px;' : 'width: 130px; display: none;'
                ],
                'input' => 'string',
                'html5' => true,
                'scale' => 2,
                'constraints' => [new Positive(['message' => 'Valor invalido!'])],
                'required' => false,
            ])
            
            ->add('observacao', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Caso queira, insira aqui suas observações.'
                ],
                'required' => false,
            ])

            ->add('adicionarMateriais', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'radio-button-row radio-checkbox-input'
                ],
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'empty_data' => '',
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
            ->add('materiais', CollectionType::class, [
                'label' => false,
                'entry_type' => MaterialType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('finalizar', SubmitType::class, [
                'label' => 'Finalizar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default',
                    'style' => 'width: 151px; font-weight: bold;'
                ],
            ])
            ->add('camposPersonalizados', EntityType::class, [
                'label' => false,
                'class'     => CamposPersonalizados::class,
                'query_builder' => function (CamposPersonalizadosRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.createdBy = :user')
                        ->andWhere('c.active = 1')
                        ->setParameter('user', $this->user);
                },
                'label_attr' => [
                    'class' => 'quais-dados-obter mb-3'
                ],
                'attr' => [
                    'class' => 'flex-row-default flex-wrap'
                ],
                'choice_label' => 'nome',
                'expanded'  => true,
                'multiple'  => true,
            ])
            ->add('ordemCampos', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => OrdemCamposType::class,
                'label' => false,
            ])
            ->add('ordemCamposArray', HiddenType::class, [
                'label' => false,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConfiguracaoQuiz::class,
            'quizPontuacao' => 0,
            'user' => null
        ]);
    }
}
