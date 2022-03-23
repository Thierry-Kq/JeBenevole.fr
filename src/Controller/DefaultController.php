<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Address;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {
        return $this->render('default/homepage.html.twig');
    }

    /**
     * @Route("/rgpd", name="rgpd")
     */
    public function rgpd(): Response
    {
        return $this->render('default/rgpd.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $informations = $form->getData();
            $user = $this->getUser();
            if ($user) {
                $informations += [
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getlastName(),
                    'email' => $user->getEmail(),
                    'phone' => $user->getCellNumber(),
                    'member' => true
                ];
            }
            //config of sender and receiver email in .env
            $email = (new TemplatedEmail())
                ->from(new Address($this->getParameter('contact_sender'), 'L\'équipe JeBénévole.fr'))
                ->to(new Address($this->getParameter('contact_receiver')))
                ->subject('Nouvelle demande sur JeBénévole.fr')
                ->htmlTemplate('default/contact_email.html.twig')
                ->context(['informations' => $informations]);
            $mailer->send($email);
            $this->addFlash('success', 'contact_form_flash_success');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
