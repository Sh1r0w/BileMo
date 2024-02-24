<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Repository\MobileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

class GetMobileController extends AbstractController
{

   /**
    * This PHP function retrieves a paginated list of mobile attributes from a repository, utilizing
    * caching and serialization before returning the data as a JSON response.
    * 
    * @param MobileRepository mobileRepository `MobileRepository` is a service that provides methods to
    * interact with the database and retrieve mobile data. In this context, it is used to fetch a
    * paginated list of mobile attributes.
    * @param SerializerInterface serializer The `` parameter in the code snippet refers to
    * an instance of `SerializerInterface`. This interface is typically used in Symfony applications
    * for serializing data into a specific format, such as JSON, XML, or YAML, and deserializing data
    * back into PHP objects.
    * @param Request request The `getMobile` method is a controller method in a Symfony application
    * that retrieves a list of mobile attributes. It accepts the following parameters:
    * @param TagAwareCacheInterface cachePool The `` parameter in the code snippet refers to
    * an instance of `TagAwareCacheInterface`. This interface provides a way to interact with a cache
    * system that supports tagging. In the code, it is used to cache the results of the `getMobile`
    * method based on the provided page and
    * 
    * @return JsonResponse The `getMobile` method returns a JSON response containing a list of mobile
    * attributes. The list is retrieved from the cache if available, otherwise it is fetched from the
    * `MobileRepository` using pagination based on the `page` and `limit` parameters provided in the
    * request. The response is serialized into JSON format using the `SerializerInterface` and returned
    * as a `JsonResponse` with HTTP status code
    */
    #[OA\Response(response:200, description: "Retourne la liste des mobiles Attributes", content: new Model(type: Mobile::class))]
    #[OA\Parameter(name: "page", in: "query", description: "La page que l'on veut récupérer")]
    #[OA\Parameter(name: "limit", in: "query", description: "Le nombre d'éléments que l'on veut récupérer")]
    #[OA\Tag(name: "Mobile")]
    #[Route('/api/mobile', name: 'app_get_mobile', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour voir les mobiles')]
    public function getMobile(MobileRepository $mobileRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $idCache = "GetAllMobile-" . $page . "-" . $limit;


        $mobileList = $cachePool->get($idCache, function(ItemInterface $item) use ($mobileRepository, $page, $limit) {
            echo("Pas de cache");
            $item->tag("mobileCaches");
            return $mobileRepository->mobilePaginated($page, $limit);
        });
        
        
        $jsonMobileList = $serializer->serialize($mobileList, 'json');
        return new JsonResponse($jsonMobileList, Response::HTTP_OK, [], true);
        
        
    }

    /**
     * This PHP function retrieves and returns a mobile object by its ID in JSON format.
     * 
     * @param Mobile mobile The `mobile` parameter in the code snippet represents an instance of the
     * `Mobile` class. It is being injected into the `getOneMobile` method as a dependency injection.
     * This parameter is used to retrieve a specific mobile object based on the provided ID in the
     * route.
     * @param SerializerInterface serilizer The parameter `` in the code snippet refers to an
     * instance of the `SerializerInterface` class. In this context, it is used for serializing the
     * `Mobile` object into JSON format before returning it as a `JsonResponse`.
     * 
     * @return JsonResponse This code snippet is a Symfony controller method that retrieves a single
     * Mobile entity by its ID and returns it as a JSON response. The method is annotated with OpenAPI
     * annotations for documentation purposes. The `getOneMobile` method takes a `Mobile` entity and a
     * `SerializerInterface` as parameters. It serializes the `Mobile` entity into JSON format using
     * the serializer and returns a JsonResponse with the serialized
     */
    #[OA\Response(response:200,description: "Retourne un Mobile grace à son ID", content: new Model(type: Mobile::class))]
    #[OA\Tag(name: "Mobile")]
    #[Route('/api/mobile/{id}', name: 'detailMobile', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour voir les mobiles')]
    public function getOneMobile(Mobile $mobile, SerializerInterface $serilizer): JsonResponse
    {
        $jsonMobile = $serilizer->serialize($mobile, 'json');
        return new JsonResponse($jsonMobile, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
