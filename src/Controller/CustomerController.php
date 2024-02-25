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

    /**
     * This PHP function creates a new customer entity, assigns it to the current user, persists it to
     * the database, and returns the serialized customer data along with a HTTP 201 Created response
     * and a location header.
     * 
     * @param Request request The code snippet you provided is a PHP function that handles the creation
     * of a customer in an API endpoint. Let me explain the parameters used in this function:
     * @param EntityManagerInterface em The "em" parameter in the code snippet refers to the
     * EntityManagerInterface, which is used for managing entities in Doctrine ORM (Object-Relational
     * Mapping) within a Symfony application. The EntityManagerInterface allows you to perform
     * operations such as persisting, updating, and deleting entities in the database.
     * @param UrlGeneratorInterface ug The `` parameter in the code snippet refers to the
     * `UrlGeneratorInterface` service in Symfony. This service is used for generating URLs in Symfony
     * applications. In the provided code, it is used to generate the URL for the newly created
     * customer resource in the `createCustomer` method.
     * 
     * @return JsonResponse A JsonResponse containing the serialized customer data is being returned.
     * The HTTP status code is set to 201 (Created), and the response includes a "Location" header with
     * the URL to access the newly created customer resource.
     */
    #[OA\Response(response:200,description: "Crée un client", content: new Model(type: Customer::class))]
    #[OA\Tag(name: "Client")]
    #[OA\Post(path: '/api/customer', operationId: 'createCustomer', 
    parameters: [new OA\Parameter(name: "name", in: 'path', required: true, schema: new OA\Schema(type: 'string'))])]
    //#[OA\Parameter(name: "name", in: "path", required: true, description: "Nom du client")]
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

    /**
     * This PHP function retrieves and returns a list of customers belonging to a specific user in JSON
     * format.
     * 
     * @return JsonResponse This code snippet is a PHP function that retrieves a list of customers
     * belonging to a specific user. It uses Symfony annotations for defining the response status code,
     * route, access control, and serialization context. The function fetches the customer list from
     * the database based on the current user's ID, serializes the data using the Symfony Serializer
     * component, and returns a JSON response containing the list of customers.
     */
    #[OA\Response(response:200, description: "Retourne la liste des clients d'un utilisateur", content: new Model(type: Customer::class))]
    #[OA\Tag(name: "Client")]
    #[Route('/api/customer', name: 'customer', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Merci de vous connectez')]
    public function GetCustomer(): JsonResponse
    {
        $customerList = $this->customerRepository->findBy(['user' => $this->getUser()->getId()]);
        $context = SerializationContext::create()->setGroups(["getCustomers"]);
        $jsonCustomerList = $this->serializer->serialize($customerList, 'json', $context);
        return new JsonResponse($jsonCustomerList, Response::HTTP_OK, [], true);

    }

    /**
     * This PHP function retrieves a specific customer's details and returns them in JSON format if the
     * authenticated user matches the customer, otherwise it returns an unauthorized response.
     * 
     * @param Customer customer The `customer` parameter in the code snippet represents a Customer
     * object that is being retrieved based on the `id` provided in the route `/api/customer/{id}`. The
     * method `getOneCustomer` is responsible for fetching and returning the details of a specific
     * customer.
     * 
     * @return JsonResponse A JsonResponse is being returned with the HTTP status code 401
     * (UNAUTHORIZED) if the condition `->getUser()->getId() == ->getUser()->getId()` is
     * not met.
     */
    #[OA\Response(response:200, description: "Retourne un client de utilisateur", content: new Model(type: Customer::class))]
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

    /**
     * This PHP function deletes a customer if the authenticated user has the necessary permissions.
     * 
     * @param Customer customer The `customer` parameter in the code snippet represents an instance of
     * the `Customer` class. It is being passed to the `deleteCustomer` method as a parameter. The
     * method is responsible for deleting a customer entity from the database.
     * @param EntityManagerInterface em The "em" parameter in the code snippet stands for
     * EntityManagerInterface. It is used in Symfony applications to interact with the database and
     * perform operations such as persisting, updating, and removing entities. In this specific
     * function `deleteCustomer`, the EntityManagerInterface is used to remove the specified Customer
     * entity from the
     * 
     * @return JsonResponse A JsonResponse with HTTP status code 200 (OK) is being returned if the
     * customer is successfully deleted. If the user attempting to delete the customer is not
     * authorized, a JsonResponse with HTTP status code 401 (UNAUTHORIZED) is being returned.
     */
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
