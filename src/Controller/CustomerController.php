<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CustomerController extends AbstractController
{
    #[Route('/api/customer', name: 'app_customer', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Merci de vous connectez')]
    public function GetCustomer(CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        $customerList = $customerRepository->findBy(['user' => $this->getUser()->getId()]);
        $context = SerializationContext::create()->setGroups(["getCustomers"]);
        $jsonCustomerList = $serializer->serialize($customerList, 'json', $context);
        return new JsonResponse($jsonCustomerList, Response::HTTP_OK, [], true);

    }
}
