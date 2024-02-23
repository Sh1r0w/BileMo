<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

class CustomerController extends AbstractController
{

    public function __construct(
        public CustomerRepository $customerRepository,
        public SerializerInterface $serializer,
    ) {}

    #[OA\Response(response:200,description: "Crée un client", content: new Model(type: Customer::class))]
    #[OA\Tag(name: "Client")]
    #[OA\Parameter(name: "name", description: "Nom du client")]
    #[Route ('/api/customer', name: 'createCustomer', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Merci de vous connectez')]
    public function createCustomer(Request $request, EntityManagerInterface $em, UrlGeneratorInterface $ug,): JsonResponse
    {
        $customer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json');
        $customer->setUser($this->getUser());
        $em->persist($customer);
        $em->flush();

        $context = SerializationContext::create()->setGroups(["getCustomers"]);
        $jsonCustomer = $this->serializer->serialize($customer, 'json', $context);

        $location = $ug->generate('detailCustomer', ['id' => $customer->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCustomer, Response::HTTP_CREATED, ["Location" => $location], true);

    }

    #[OA\Response(response:200,description: "Retourne la liste des clients d'un utilisateur", content: new Model(type: Customer::class))]
    #[OA\Tag(name: "Client")]
    #[Route('/api/customer', name: 'app_customer', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Merci de vous connectez')]
    public function GetCustomer(): JsonResponse
    {
        $customerList = $this->customerRepository->findBy(['user' => $this->getUser()->getId()]);
        $context = SerializationContext::create()->setGroups(["getCustomers"]);
        $jsonCustomerList = $this->serializer->serialize($customerList, 'json', $context);
        return new JsonResponse($jsonCustomerList, Response::HTTP_OK, [], true);

    }

    #[OA\Response(response:200,description: "Retourne un client de utilisateur", content: new Model(type: Customer::class))]
    #[OA\Tag(name: "Client")]
    #[Route ('/api/customer/{id}', name: 'detailCustomer', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Merci de vous connectez')]

    public function getOneCustomer(Customer $customer): JsonResponse
    {
        if ($customer->getUser()->getId() == $this->getUser()->getId()){
        $context = SerializationContext::create()->setGroups(["getCustomers"]);
        $jsonCustom = $this->serializer->serialize($customer, 'json', $context);
        return new JsonResponse($jsonCustom, Response::HTTP_OK, ['accept' => 'json'], true);
    } 

    return new JsonResponse (Response::HTTP_UNAUTHORIZED);

    }

    #[OA\Response(response:200,description: "Supprime un client", content: new Model(type: Customer::class))]
    #[OA\Tag(name: "Client")]
    #[Route ('/api/customer/{id}', name: 'deleteCustomer', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: 'Merci de vous connectez')]

    public function deleteCustomer(Customer $customer, EntityManagerInterface $em): JsonResponse
    {
        if ($customer->getUser()->getId() == $this->getUser()->getId()){
            $em->remove($customer);
            $em->flush();
            
            return new JsonResponse(Response::HTTP_OK);
        }
        
        return new JsonResponse(Response::HTTP_UNAUTHORIZED);
    }
}
