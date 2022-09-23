<?php

namespace App\Form;

use App\Entity\Subject;
use App\Entity\Forum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'name',
                'required' => true,
            ])
            ->add('description', TextType::class, [
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
