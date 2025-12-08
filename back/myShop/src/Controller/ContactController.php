<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'api.contact.send', methods: ['POST'])]
    public function send(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['message'])) {
            return $this->json(['error' => 'Missing settings : email and message'], 400);
        }

        $email = (new Email())
            ->from($data['email'])
            ->to('contact@monsite.com')
            ->subject('Nouveau message de contact')
            ->text($data['message']);

        $mailer->send($email);

        return $this->json(['status' => 'Email sent successfully']);
    }
}
