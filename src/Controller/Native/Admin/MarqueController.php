<?php

namespace App\Controller\Native\Admin;


use App\Manager\MarqueManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class MarqueController
 * @package App\Controller\Native\Admin
 */
class MarqueController
{
    /**
     * @var MarqueManagerInterface
     */
    private $manager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer,MarqueManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/marque/search/{_query?}", name="search_marque", methods={"POST", "GET"}, options={"expose" = true})
     */
    public function searchAction($_query)
    {
        if ($_query)
        {
            $result = $this->manager->getMarqueLikeNom($_query);
        } else {
            $result = $this->manager->getRepository()->findAll();
        }
        /*$normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) { return $object->getId(); });
        // setting up the serializer
        $normalizers = [
            $normalizer
        ];
        $encoders =  [
            new JsonEncoder()
        ];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($data, 'json');*/
        $data = $this->serializer->serialize($result, 'json',['groups' => ['search']]);
        return new JsonResponse($data, 200, [], true);
    }
}