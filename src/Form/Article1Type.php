<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class Article1Type extends AbstractType
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
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image File :',
                'required' => false,
                'image_uri' => true,
                'download_uri' => false,
                // 'mapped' => false,
            ])

            ->add('categorie_id', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Categories :',
                'expanded' => true,
                'multiple' => false,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
