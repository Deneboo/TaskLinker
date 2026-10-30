<?php

namespace App\Controller;

use Scheb\TwoFactorBundle\Security\Authentication\Token\TwoFactorToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

    #[Route('/authentication/resend', name: '2fa_resend', methods: ['POST'])]
    public function resend(
        Request $request,
        TokenStorageInterface $tokenStorage,
        AuthenticationUtils $authenticationUtils,
        #[Autowire(service: 'scheb_two_factor.security.email.code_generator')] object $codeGenerator,
    ): Response {
        // Debug global pour voir ce qui arrive sur cette route
        if ($request->isMethod('POST')) {
            // dd('Requete POST reçue !', $request->request->all());
        }
        $token = $tokenStorage->getToken();

        // Si l'utilisateur clique sur "Renvoyer le code" (en POST)
        if ($request->isMethod('POST') && $request->request->has('resend')) {
            if ($token instanceof TwoFactorToken) {
                $user = $token->getUser();

                // dd('LE BOUTON RESEND A ETE CLIQUE !', $user);
                $codeGenerator->generateAndSend($user);
                // dd('LE BOUTON RESEND A ETE CLIQUE !', $codeGenerator->generateAndSend($user));
                $this->addFlash('success', 'Un nouveau code de vérification vous a été envoyé.');
            }

            return $this->redirectToRoute('2fa_login');
        }

        // Récupération de l'erreur d'authentification 2FA
        $authenticationError = $authenticationUtils->getLastAuthenticationError();

        // Affichage du formulaire en transmettant toutes les variables attendues par le template
        return $this->render('security/2fa_form.html.twig', [
            'authenticationError' => $authenticationError,
            'authCodeParameterName' => '_auth_code',
            'trustedParameterName' => '_trusted',
            'displayTrustedOption' => true,
            'isCsrfProtectionEnabled' => false, // Mettez true si vous activez la protection CSRF pour le 2FA
            'csrfTokenVariableName' => '_csrf_token',
        ]);
    }
}
