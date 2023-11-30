<?php

namespace App\Form\Type\MinhaConta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Quiz\CamposPersonalizados;
use App\Repository\CamposPersonalizadosRepository;
use App\Validator\NomeCampoPersonalizadoExiste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotNull;

class CamposPersonalizadosType extends AbstractType
{
    private $user;
    private $campoId;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->user = $options['user'];
        $this->campoId = $options['campoId'];

        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome do campo',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Exemplo: Nome'
                ],
                'constraints' => new NomeCampoPersonalizadoExiste(['userId' => $this->user, 'campoId' => $this->campoId]),
                'required' => true
            ])
            ->add('label', TextType::class, [
                'label' => 'Enunciado do campo',
                'attr' => [
                    'class' => 'form-default',
                    'placeholder' => 'Exemplo: Qual seu nome?'
                ],
                'required' => true
            ])
            ->add('tipo', ChoiceType::class, [
                'label' => 'Tipo do campo',
                'choices'  => [
                    'Escolha uma opção' => null,
                    'Campo aberto - Email' => 'EMAIL_INPUT',
                    'Campo aberto - Número' => 'NUMBER_INPUT',
                    'Campo aberto - Telefone' => 'PHONE_INPUT',
                    'Campo aberto - Texto' => 'TEXT_INPUT',
                    'Campo aberto - Texto grande' => 'TEXT_AREA',
                    'Campo aberto - URL' => 'URL_INPUT',
                    'Campo de seleção -  Escolha única' => 'RADIO_BUTTON',
                    'Campo de seleção - Caixa de seleção' => 'COMBO_BOX',
                    'Campo de seleção - Escolha múltipla' => 'MULTIPLE_CHOICE',
                    'Campo de seleção - Verificação' => 'CHECK_BOX',
                ],
                'choice_attr' => [
                    'Escolha uma opção' => ['disabled' => true],
                ],
                'placeholder' => false,
                'attr' => [
                    'class' => 'form-default form-select'
                ],
                'constraints' => [new NotNull(['message' => 'Selecione uma opção!'])],
                'required' => true,
            ])
            ->add('fonteExterna', CheckboxType::class, [
                'label' => 'Endpoint dados fonte externa',
                'row_attr' => [
                    'class' => 'radio-checkbox-input',
                ],
                'required' => false,
            ])
            ->add('endpointFonteExterna', TextType::class, [
                'label' => 'Endpoint fonte externa',
                'attr' => [
                    'class' => 'form-default',
                ],
                'required' => false
            ])
            ->add('dependeOutroCampo', CheckboxType::class, [
                'label' => 'Campo dependente',
                'row_attr' => [
                    'class' => 'radio-checkbox-input',
                ],
                'required' => false,
            ])
            ->add('campoDependente', EntityType::class, [
                'class' => CamposPersonalizados::class,
                'query_builder' => function (CamposPersonalizadosRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.createdBy = :user')
                        ->andWhere('c.active = 1')
                        ->andWhere('c.id != :campoId')
                        ->setParameter('user', $this->user)
                        ->setParameter('campoId', $this->campoId);
                },
                'choice_label' => 'nome',
                'label' => 'Campo dependente',
                'placeholder' => 'Selecione uma das opções',
                'multiple' => false,
                'attr' => [
                    'class' => 'form-default form-select',
                ],
                'required' => false,
            ])
            ->add('opcoes', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => OpcoesCamposPersonalizadosType::class,
                'label' => false,
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
            'data_class' => CamposPersonalizados::class,
            'user' => null,
            'campoId' => ''
        ]);
    }
}
