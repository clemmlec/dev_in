<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrer un mots de passe s\'il vous plait',
                        ]),
                        // new Length([
                        //     'min' => 6,
                        //     'minMessage' => 'Votre mots de passe doit faire minimum {{ limit }} characteres',
                        //     // max length allowed by Symfony for security reasons
                        //     'max' => 4096,
                            
                        // ]),
                        new Regex('/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/',
                        'Votre mot de passe doit comporter au moins 6 caractères, 
                        une lettre majuscule, une lettre miniscule 
                        et 1 chiffre sans espace blanc'),
                        
                    ],
                    'label' => 'Mots de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Repeter le mots de passe',
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'J\'accepte la politique de confidentialité',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez accepter les parametre de confidentialité',
                    ]),
                ],
            ])

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
            ->add('active', CheckboxType::class, [
                'help' => 'actif/mute',
            ])

            ->add('imageFile', VichImageType::class, [
                'label' => 'Avatar :',
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
