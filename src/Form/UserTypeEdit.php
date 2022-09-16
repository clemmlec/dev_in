<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserTypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'nom',
                'required' => true,
            ])
            // ->add('email', EmailType::class, [
            //     'label' => 'Email :',
            //     'required' => true
            // ])

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Editeur' => 'ROLE_EDITEUR',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'label' => 'Roles :',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('style', ChoiceType::class, [
                'choices' => [
                    'bleu' => 'bleu',
                    'vert' => 'vert',
                    'jaune' => 'jaune',
                ],
                'label' => 'Style :',
                'required' => false,
                'expanded' => true,
            ])
            ->add('visible', CheckboxType::class, [
                'help' => 'actif/mute',
            ])

            ->add('imageFile', VichImageType::class, [
                'label' => 'Image File :',
                'required' => false,
                'image_uri' => true,
                'download_uri' => false,
                // 'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
