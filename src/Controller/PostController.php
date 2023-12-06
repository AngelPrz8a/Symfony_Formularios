<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    // #[Route('/post', name: 'app_post')]
    // public function index(): Response
    // {
    //     return $this->render('post/index.html.twig', [
    //         'controller_name' => 'PostController',
    //     ]);
    // }

    /**
     * Formulario para crear un post
     */
    #[Route('/post/create', name: 'post_create', methods:["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->addFlash("success", "Se creo con exito");
            return $this->redirectToRoute("post_create");
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Formulario para editar un post
     */
    #[Route('/post/{id}/edit', name: 'post_edit', methods:["GET", "POST"])]
    public function edit(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            //$entityManager->persist($form->getData()); // LINEA OPCIONAL
            $entityManager->flush();

            $this->addFlash("success", "Se edito con exito");
            return $this->redirectToRoute("post_edit",[
                "id"=>$post->getId()
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
