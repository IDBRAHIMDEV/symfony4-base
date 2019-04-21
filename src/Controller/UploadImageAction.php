<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UploadImageAction
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager) {

        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request)
    {
        $image = new Image();

        $form = $this->formFactory->create(ImageType::class, $image);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($image);
            $this->entityManager->flush();

            return $image;
        }

    }

}
