<?php

namespace App\Controller;

use App\Entity\Owner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OwnerController extends AbstractController
{
    /**
     * @Route("/auth/Register", name="auth_Register" , methods={"POST"})
     */
    public function Register(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {

        $jsonResponse = $request->getContent();

        try {
            $owner = $serializer->deserialize($jsonResponse, Owner::class, 'json');

            $password = $encoder->encodePassword($owner, $owner->getPassword());

            $owner->setPassword($password);

            $errors = $validator->validate($owner);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManagerInterface->persist($owner);

            $entityManagerInterface->flush();

            return $this->json($owner, 201, [], ['groups' => 'seller']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        } catch (UniqueConstraintViolationException $e) {
            return $this->json([
                'status' => 400,
                'message' => "duplicate value",
            ], 400);
        }
    }
}
