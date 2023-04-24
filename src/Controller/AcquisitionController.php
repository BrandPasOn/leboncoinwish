<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Acquisition;
use App\Form\AcquisitionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AcquisitionController extends AbstractController
{
    #[Route('/acquisition/{post}/buy', name: 'app_acquisition')]
    public function buy(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $address = $user->getAddress();
        $id = $user->getId();
        $bank = $user->getBank();

        if($address->isEmpty())
        {
            return $this->redirectToRoute('app_address_create', ["user" => $id]);
        } else {
            
            $acquistion = new Acquisition();
            $acquistion->setUser($user);
            $acquistion->setPost($post);
    
            $form = $this->createForm(AcquisitionType::class, $acquistion);
    
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if($bank->getAmount() < $post->getPrice() ) {

                    $this->addFlash('error', "You don't have enough money in your bank");


                } else {

                    $bank->setAmount($bank->getAmount() - $post->getPrice());
                    $post->setIsVisible(false);

                    $entityManager->persist($post);
                    $entityManager->persist($bank);
                    $entityManager->persist($acquistion);
                    $entityManager->flush();
        
                    return $this->redirectToRoute('app_dashboard');
                }
    
            }
            return $this->render('acquisition/index.html.twig', [
                'controller_name' => 'AcquisitionController',
                'form' => $form->createView(),
                'bank' => $bank
            ]);
        }

        
    }
}
