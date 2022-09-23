<?php

namespace App\Form;

use App\Entity\Forum;
use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'content',
                'required' => true,
            ])
         
            ->add('forum', EntityType::class, [
                'class' => Forum::class,
                'label' => 'Forum :',
                'expanded' => true,
                'multiple' => false,
                'choice_label' => 'nom',
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
