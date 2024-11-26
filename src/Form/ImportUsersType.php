<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ImportUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csvFile', FileType::class, [
                'label' => 'Fichier CSV',
                'constraints' => [
                    new File([
                        'mimeTypes' => ['text/csv', 'application/csv'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier CSV valide.',
                    ])
                ],
            ]);
    }
}
