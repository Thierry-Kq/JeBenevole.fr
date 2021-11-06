<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\EmailVerifier;
use App\Security\LoginAuthenticator;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        SluggerInterface $slugger,
        UserAuthenticatorInterface $authenticator,
        LoginAuthenticator $login,
        UsersRepository $usersRepository
    ): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($usersRepository->findOneBy(['email' => $form->get('email')->getData()])) {
                $this->addFlash('warning', 'an_error_occurred');
                return $this->redirectToRoute('app_register');
            }

            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();

            $user->setSlug($slugger->slug($user->getNickname()));

            $entityManager->persist($user);

            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash('warning', 'not_unique_nickname');

                return $this->redirectToRoute('app_register');
            }
            $entityManager->flush();

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@jebenevole.fr', 'JeBenevole'))
                    ->to($user->getEmail())
                    ->subject('Confirmez votre email svp')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $authenticator->authenticateUser($user, $login, $request);

            // todo : add a flash to tell the user he have 1 hour to confirm his mail,
            return $this->redirectToRoute('associations');

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verification/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('homepage');
    }
}
