<?php

namespace App\Controller\Api;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


trait TraitApiController
{
    private SerializerInterface $serializer;
    private $passwordEncoder;
    private $session;

    function __construct(UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
    {
        $this->serializer = SerializerBuilder::create()->build();
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
    }
}
