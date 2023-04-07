<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Acquisition;
use App\Form\AcquisitionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AcquisitionController extends AbstractController
{
    #[Route('/acquisition/{post}/buy', name: 'app_acquisition')]
    public function buy(Post $post, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $acquistion = new Acquisition();
        $acquistion->setUser($user);
        $acquistion->setPost($post);

        /* $form = $this->createForm(AcquisitionType::class, $acquistion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($acquistion);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        } */

        return $this->render('acquisition/index.html.twig', [
            'controller_name' => 'AcquisitionController',
        ]);
    }
}
