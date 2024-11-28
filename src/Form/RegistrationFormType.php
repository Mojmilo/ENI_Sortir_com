<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Site;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo : ',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"pseudo"],
                'attr'=>[
                    'id'=>'default-input',
                    'class'=>'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => '...'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom : ',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"firstName"],
                'attr'=>[
                    'id'=>'default-input',
                    'class'=>'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => '...'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom : ',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"lastName"],
                'attr'=>[
                    'id'=>'default-input',
                    'class'=>'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => '...'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone : ',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"phone"],
                'attr'=>[
                    'id'=>'default-input',
                    'class'=>'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => '...'
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email : ',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"email"],
                'attr'=>[
                    'id'=>'default-input',
                    'class'=>'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => '...'
                ]
            ])
            ->add('active', ChoiceType::class, [
                'label' => 'Visibilité',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"active"],
                'attr'=>[
                    'class'=>'flex items-center me-4 mb-5'
                ],
                'choices' => [
                    'Visible' => 1,
                    'Invisible' => 0
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 1,
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600', 'role' => 'switch'];
                }
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'label' => 'Ville de rattachement',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"site"],
                'attr'=>[
                    'class'=>'mb-5 block font-medium bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => 'Choisissez une ville...'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Mot de passe : ',
                'attr' => ['autocomplete' => 'new-password'],
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for' => "plainPassword"],
                'attr'=>[
                    'class'=>'mb-5 block font-medium bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => '...'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez compléter le champ.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit être composé d\'au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
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
