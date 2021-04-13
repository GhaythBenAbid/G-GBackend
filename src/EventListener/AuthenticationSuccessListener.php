<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();


        if (!$user instanceof UserInterface) {
            return;
        }



        $data['user'] = array(
            "userId" => $user->getId(),
            "username" => $user->getUsername(),
            "email" => $user->getEmail(),
            "First_Name" => $user->getFirstName(),
            "Last_Name" => $user->getLastName(),
            "Role" => $user->getRoles()[0],

        );


        $event->setData($data);
    }
}
