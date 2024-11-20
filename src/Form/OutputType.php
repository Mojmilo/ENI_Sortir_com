<?php

namespace App\Form;

use App\Entity\member;
use App\Entity\Output;
use App\Entity\site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
            ])
            ->add('startDatetime', null, [
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text',
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée',
            ])
            ->add('registrationDeadline', null, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
            ])
            ->add('maxNumberRegistration', NumberType::class, [
                'label' => 'Nombre de places',
            ])
            ->add('exitInfos', TextareaType::class, [
                'label' => 'Description et infos',
            ])
            ->add('site', EntityType::class, [
                'label' => 'Site',
                'class' => site::class,
                'choice_label' => 'name',
                'disabled' => true,
            ])
            ->add('location', LocationType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Output::class,
        ]);
    }
}
