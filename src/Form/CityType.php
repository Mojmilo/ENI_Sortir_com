<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la ville',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom de la ville ne peut pas être vide.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom de la ville ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le code postal est obligatoire.']),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'Le code postal doit comporter exactement {{ limit }} chiffres.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
