<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use App\Form\ResetPasswordRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, 
    EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // return $this->redirectToRoute('app_output_index');
            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/forgotten-password', name: 'app_forgotten_password', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        

    $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            // Vérifier si l'email existe dans la base de données
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                // Générer un token pour la réinitialisation
                $token = Uuid::v4(); // Générer un UUID unique
                $user->setPasswordResetToken((string) $token);
                $entityManager->flush();

                // Construire le lien de réinitialisation
                $resetUrl = $this->generateUrl('app_reset_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // Envoyer un email à l'utilisateur avec le lien de réinitialisation
                $email = (new Email())
                    ->from('no-reply@votreapp.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation du mot de passe')
                    ->html('<p>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : <a href="' . $resetUrl . '">Réinitialiser le mot de passe</a></p>');

                $mailer->send($email);

                // Message flash pour informer l'utilisateur
                $this->addFlash('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
            } else {
                // Si l'email n'existe pas
                $this->addFlash('error', 'Aucun compte trouvé avec cet email.');
            }

            return $this->redirectToRoute('app_reset_password_request');
        }

        return $this->render('registration/forgotten_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reinitialiser-mot-de-passe/{token}', name: 'app_reset_password_reset')]
    public function reset(Request $request, string $token, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['passwordResetToken' => $token]);

        if (!$user) {
            // Si le token est invalide ou expiré
            throw $this->createNotFoundException('Token invalide ou expiré');
        }

        // Formulaire pour entrer un nouveau mot de passe
        // (création d'un formulaire de changement de mot de passe ici)
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();

            // Encoder le mot de passe
            $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($encodedPassword);
            $user->setPasswordResetToken(null); // Réinitialiser le token
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('app_login'); // Rediriger vers la page de connexion
        }

        return $this->render('reset_password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
