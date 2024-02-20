<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    #[Route('/api/customer', name: 'app_customer', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Merci de vous connectez')]
    public function GetCustomer(CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        $customerList = $customerRepository->findBy(['user' => $this->getUser()->getId()]);
        $jsonCustomerList = $serializer->serialize($customerList, 'json', ['groups' => 'getCustomers']);
        return new JsonResponse($jsonCustomerList, Response::HTTP_OK, [], true);

    }
}
