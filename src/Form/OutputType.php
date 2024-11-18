<?php

namespace App\Form;

use App\Entity\location;
use App\Entity\member;
use App\Entity\Output;
use App\Entity\site;
use App\Entity\status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startDatetime', null, [
                'widget' => 'single_text',
            ])
            ->add('duration')
            ->add('registrationDeadline', null, [
                'widget' => 'single_text',
            ])
            ->add('maxNumberRegistration')
            ->add('exitInfos')
            ->add('status', EntityType::class, [
                'class' => status::class,
                'choice_label' => 'id',
            ])
            ->add('organisator', EntityType::class, [
                'class' => member::class,
                'choice_label' => 'id',
            ])
            ->add('members', EntityType::class, [
                'class' => member::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('site', EntityType::class, [
                'class' => site::class,
                'choice_label' => 'id',
            ])
            ->add('location', EntityType::class, [
                'class' => location::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Output::class,
        ]);
    }
}
