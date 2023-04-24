<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/dashboard/{user}/createAddress', name: 'app_address_create')]
    public function create(User $user, AddressType $form, Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = New Address();
        $address->setUser($user);

        // get the last route
        $route = $request->headers->get('referer');

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('address/index.html.twig', [
            'form' => $form->createView(),
            'address' => $address
        ]);
    }

    #[Route('/dashboard/updateAddress/{address}', name: 'app_address_update')]
    public function update(Address $address, AddressType $form, Request $request, EntityManagerInterface $entityManager ,AddressRepository $repo): Response
    {
        $address = $repo->find($address);

        $form = $this->createForm(AddressType::class, $address);

        // get the last route
        $route = $request->headers->get('referer');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('address/index.html.twig', [
            'form' => $form->createView(),
            'address' => $address
        ]);
    }

    #[Route('/dashboard/deleteAddress/{address}', name: "app_address_delete", methods: ['DELETE'])]
    public function delete(AddressRepository $repo, Address $address): Response
    {
        $repo->remove($address, true);

        return $this->redirectToRoute('app_dashboard');
    }
}
