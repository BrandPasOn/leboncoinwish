<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/dashboard/{user}/address/create', name: 'app_address_create')]
    public function create(User $user, AddressType $form, Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = New Address();
        $address->setUser($user);

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('address/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
