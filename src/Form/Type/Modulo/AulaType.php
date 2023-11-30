<?php

namespace App\Form\Type\Modulo;

use App\Entity\Curso\Aula;
use App\Entity\Curso\Modulo;
use App\Entity\Quiz\Quiz;
use App\Form\Type\Material\MaterialType;
use App\Repository\QuizRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class AulaType extends AbstractType
{
    private $curso_id;
    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->curso_id = $options['curso_id'];
        $this->user = $options['user'];

        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome da aula',
                'attr' => [
                    'class' => 'form-default'
                ],
                'required' => true,
            ])
            
            ->add('tipo', ChoiceType::class, [
                'label' => 'Tipo de aula',
                'choices'  => [
                    'Quiz/Teste' => 'quiz'
                ],
                'attr' => [
                    'class' => 'form-default form-select'
                ],
                'required' => true,
            ])
            ->add('quiz', EntityType::class, [
                'label' => 'Quiz',
                'class' => Quiz::class,
                'query_builder' => function (QuizRepository $er) {
                    return $er->createQueryBuilder('quiz')
                        ->where('quiz.createdBy = :user')
                        ->andWhere('quiz.active = true')
                        ->andWhere('quiz.pesquisa = 0')
                        ->setParameter('user', $this->user)
                        ->orderBy('quiz.created', 'ASC');
                },
                'attr' => [
                    'class' => 'form-default form-select select-quiz',
                ],
                'choice_label' => 'nome',
                'placeholder' => 'Selecione o Quiz',
                'required' => false,
            ])
            
            ->add('materiais', CollectionType::class, [
                'label' => false,
                'entry_type' => MaterialType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('modulo', Select2EntityType::class, [
                'multiple' => false,
                'label' => 'Módulo',
                'remote_route' => 'search_modulos_json',
                'remote_params' => ['curso_id' => $this->curso_id],
                'class' => Modulo::class,
                'primary_key' => 'id',
                'text_property' => 'nome',
                'minimum_input_length' => 0,
                'cache' => 0,
                'page_limit' => 10,
                'language' => 'pt',
                'attr' => [
                    'class' => 'js-select2',
                    'style' => "width: 100%;"
                ],
                'allow_clear' => true,
                'allow_add' => [
                    'enabled' => true,
                    'new_tag_text' => ' (NEW)',
                    'new_tag_prefix' => '__',
                    'tag_separators' => '[ ";" ]'
                ],
                'constraints' => [new NotBlank()],
                'mapped' => true,
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn shadow-default btn-default'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aula::class,
            'curso_id' => null,
            'validation_groups' => false,
            'user' => ''
        ]);
    }
}
