<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use App\Repository\OffersRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(OffersRepository $offersRepository): Response
    {
        $lastOffers = $offersRepository->getLastOffers('associations');
        $lastRequests = $offersRepository->getLastOffers('users');

        return $this->render('default/homepage.html.twig', [
            'lastOffers' => $lastOffers,
            'lastRequests' => $lastRequests,
        ]);
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
    public function contact(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
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
            $this->addFlash('success', $translator->trans('error_msg'));
            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
