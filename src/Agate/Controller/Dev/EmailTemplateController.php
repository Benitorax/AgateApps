<?php

namespace Agate\Controller\Dev;

use Agate\Model\ContactMessage;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class EmailTemplateController implements PublicService
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Route is located in config/routes/dev/templates.yaml.
     * No annotation here because it's only for debug in dev.
     */
    public function __invoke(Request $request)
    {
        $message = new ContactMessage();

        $message->setName($request->get('name', 'John Doe'));
        $message->setEmail($request->get('email', 'john@doe.com'));
        $message->setSubject($request->get('subject', ContactMessage::SUBJECT_AFTER_SALES_CROWDFUNDING));
        $message->setTitle($request->get('title', 'This is just a contact email'));
        $message->setMessage($request->get('message', "Lorem ipsum dolor sit amet, consectetur adipisicing elit.\nAccusantium aspernatur assumenda blanditiis ducimus eligendi eveniet fugiat laborum magni maxime quae reiciendis, veritatis, vitae voluptas.\nAd et excepturi facilis recusandae ullam."));

        return new Response($this->twig->render('agate/email/contact_email.html.twig', [
            'message' => $message,
        ]));
    }
}
