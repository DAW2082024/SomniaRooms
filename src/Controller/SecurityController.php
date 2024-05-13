<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ConfigVariable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/setup', name: 'app_security_addAdmin')]
    public function addAdmin(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $setupStatus = $entityManager->find(ConfigVariable::class, "SETUP_STATUS");

        if($setupStatus == null || $setupStatus == 1) {
            return new Response("Setup was already done");
        }


        $entityManager->beginTransaction();

        try {

            //Create system variables.
            $var_setup = new ConfigVariable();
            $var_setup->setKey("SETUP_STATUS");
            $var_setup->setValue("1");
            $var_setup->setSection("SYSTEM");
            $entityManager->persist($var_setup);


            //Create user Admin.
            $user = new User();
            $user->setUsername("admin");
            $user->setRoles(["ROLE_USER", "ROLE_ADMIN", "ROLE_SUPERADMIN"]);
            $plaintextPassword = "admin";

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            
            $entityManager->flush();
            $entityManager->commit(); 

        } catch (\Throwable $th) {
            $entityManager->rollback();
            throw $th;
        }

        return new Response("Setup completed :)");
    }
}
