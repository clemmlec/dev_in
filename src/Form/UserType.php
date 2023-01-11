<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom :',
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
                        new Regex('/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{8,})\S$/',
                            'Votre mot de passe doit comporter au moins 8 caractères,
                            une lettre majuscule, une lettre miniscule
                            et 1 chiffre sans espace blanc'
                        ),
                    ],
                    'label' => 'Mots de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Repeter le mots de passe',
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'mapped' => false,
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Avatar :',
                'required' => false,
                'image_uri' => true,
                'download_uri' => false,
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'J\'accepte la politique de confidentialité',
                'constraints' => [
                    new NotBlank([
                            'message' => 'Veuillez accepter les parametre de confidentialité',
                    ]),
                ],
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
