<?php

namespace App\Form;

use App\Entity\site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $options['is_admin'] ?? false;


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
            ]);
            // ->add('active', ChoiceType::class, [
            //     'label' => 'Visibilité',
            //     'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"active"],
            //     'attr'=>[
            //         'class'=>'flex items-center me-4 mb-5'
            //     ],
            //     'choices' => [
            //         'Visible' => 1,
            //         'Invisible' => 0
            //     ],
            //     'expanded' => true,
            //     'multiple' => false,
            //     'data' => 1,
            //     'choice_attr' => function($choice, $key, $value) {
            //         return ['class' => 'w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600', 'role' => 'switch'];
            //     }
            // ])

            // <form class="max-w-sm mx-auto">
            // "block mb-2 text-sm font-medium text-gray-900 dark:text-gray"
            // <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500">
            
            if ($isAdmin) {
            $builder->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'label' => 'Ville de rattachement',
                'label_attr' => ['class' => 'mb-3 font-bold text-gray-500 dark:text-gray-400', 'for'=>"site"],
                'attr'=>[
                    'class'=>'mb-5 block font-medium bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => 'Choisissez une ville...'
                ]
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_admin' => false, // par defaut, l'utilisateur n'est pas admin
        ]);
    }
}
