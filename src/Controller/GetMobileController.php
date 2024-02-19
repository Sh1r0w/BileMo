<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Repository\MobileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GetMobileController extends AbstractController
{
    #[Route('/api/mobile', name: 'app_get_mobile', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour voir les mobiles')]
    public function getMobile(MobileRepository $mobileRepository, SerializerInterface $serializer): JsonResponse
    {

        $mobileList = $mobileRepository->findAll();
        $jsonMobileList = $serializer->serialize($mobileList, 'json');
        return new JsonResponse($jsonMobileList, Response::HTTP_OK, [], true);
        
    }

    #[Route('/api/mobile/{id}', name: 'app_get_one_mobile', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour voir les mobiles')]
    public function getOneMobile(Mobile $mobile, SerializerInterface $serilizer): JsonResponse
    {
        $jsonMobile = $serilizer->serialize($mobile, 'json');
        return new JsonResponse($jsonMobile, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
