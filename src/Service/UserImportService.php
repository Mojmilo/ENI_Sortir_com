<?php

namespace App\Service;

use App\Entity\Site;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserImportService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importUsersFromCSV(string $filePath): void
    {
        if (($handle = fopen($filePath, 'r')) === false) {
            throw new \RuntimeException('Impossible d\'ouvrir le fichier CSV.');
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            throw new \RuntimeException('Le fichier CSV est vide ou invalide.');
        }

        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($headers, $data);

            // Validation des données de la ligne
            if (!$this->validateRow($row)) {
                continue;
            }

            $site = $this->entityManager->getRepository(Site::class)->find($row['site_id']);
            if (!$site) {
                continue;
            }

            $user = new User();
            $user->setSite($site)
                ->setFirstName($row['first_name'])
                ->setLastName($row['last_name'])
                ->setPhone($row['phone'])
                ->setEmail($row['email'])
                ->setRoles(json_decode($row['role'], true) ?? [])
                ->setPassword($row['password'])
                ->setActive((bool)$row['active'])
                ->setPseudo($row['pseudo']);

            $this->entityManager->persist($user);
        }

        fclose($handle);
        $this->entityManager->flush();
    }

    private function validateRow(array $row): bool
    {
        // Vérification minimale des données
        //TODO : Ajouter des vérifications plus poussées
        return isset($row['site_id'], $row['first_name'], $row['last_name'], $row['email']);
    }
}
