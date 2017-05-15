<?php

namespace DW\DWBundle\EventListener;

use DW\DWBundle\Event\UserEvent;
use DW\DWBundle\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;

class SendActivationEmailListener implements EventSubscriberInterface
{
    private $router;
    private $mailer;

    public function __construct(Router $router, $mailer)
    {
        $this->router = $router;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::USER_JOINED => "onUserJoined"
        );
    }

    public function sendActivationEmail(UserEvent $userEvent)
    {
        $user = $userEvent->getUser();

        $url = $this->router->generate('documentary_wire.activate',
            array("username" => $user->getUsername(), "key" => $user->getActivationKey()), true);

        $message = \Swift_Message::newInstance()
            ->setSubject('Activate your account at DocumentaryWIRE')
            ->setFrom(array('contact@documentarywire.com' => 'DocumentaryWIRE'))
            ->setTo($user->getEmail())
            ->setBody("Thanks for registering at DocumentaryWIRE!
Please activate your account by clicking on the following link: " . $url);
        $this->mailer->send($message);
    }
}