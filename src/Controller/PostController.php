<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/dashboard/{user}/post/createPost', name: 'app_post')]
    public function create(User $user, PostType $form, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $post->setUser($user);
        $post->setIsVisible(false);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }

    #[Route('/dashboard/{user}/post/{post}/changeStatus', name: 'app_post_changeStatus')]
    public function changeStatus(Post $post, EntityManagerInterface $entityManager): Response
    {
        if($post->isIsVisible()){ $post->setIsVisible(false); } else { $post->setIsVisible(true); }

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }
}
