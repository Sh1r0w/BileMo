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
