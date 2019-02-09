<?php

namespace App\Controller\Native\Admin;


use App\Manager\EtablissementManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class EtablissementController
 * @package App\Controller\Native\Admin
 *
 */
class EtablissementController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EtablissementManagerInterface
     */
    private $manager;

    public function __construct(SerializerInterface $serializer,EtablissementManagerInterface $manager)
    {
        $this->serializer = $serializer;
        $this->manager = $manager;
    }

    /**
     * @Route("/etablissement/search/{_query?}", name="search_etablissement", methods={"POST", "GET"})
     */
    public function searchAction($_query)
    {
        if ($_query)
        {
            $result = $this->manager->getEtablissementLikeNom($_query);
        } else {
            $result = $this->manager->getRepository()->findAll();
        }
        /*$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer));*/
        /*$normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) { return $object->getId(); });
        // setting up the serializer
        $normalizers = [
            $normalizer
        ];
        $encoders =  [
            new JsonEncoder()
        ];
        $serializer = new Serializer($normalizers, $encoders);*/
        //$data = $normalizer->normalize($result, null, array('groups' => 'search'));
        $data = $this->serializer->serialize($result, 'json',['groups' => ['search']]);

        return new JsonResponse($data, 200, [], true);
    }
}