<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Ancien mot de passe : ',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for' => "oldPassword"],
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'autocomplete' => 'current-password',
                    'id' => 'default-input',
                    'class' => 'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => '...'
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'invalid_message'=> 'Les mots de passe doivent correspondre.',
                'required'=> true,
                'mapped' => false,
                'label' => ' ',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for' => "newPassword"],
                'first_options' => [
                    'label' => 'Nouveau mot de passe : ',
                    'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for' => "newPassword"],
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'id' => 'default-input',
                        'class' => 'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                        'placeholder' => '...'
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe : ',
                    'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for' => "newPassword"],
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'id' => 'default-input',
                        'class' => 'mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                        'placeholder' => '...'
                    ],
                ],
                
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
