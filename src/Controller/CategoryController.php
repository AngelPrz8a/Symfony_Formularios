<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * Devuelve todas las categorias
     */
    #[Route('/category', name: 'category_index')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $entityManagerInterface->getRepository(Category::class)->findAll()
        ]);
    }

    /**
     * Retorna el formulario
     * si fue enviado y validado se guardara
     * creara un mensaje y redireccionara
     */
    #[Route("/category/create", name:"category_create", methods:["GET","POST"])]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface):Response
    {
        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->persist($form->getData());
            $entityManagerInterface->flush();

            $this->addFlash("success", "Se registro con exito");
            return $this->redirectToRoute("category_create");
        }

        return $this->render("category/create.html.twig",[
            "form"=>$form->createView(),
        ]);
    }

    /**
     * Retornara el formulario con información
     * si el formulario se envio y valido se guardara 
     * y enviara un mensaje
     * redirecionara a la ruta con el id del registro actualiado
     */
    #[Route("/category/{id}/edit", name:"category_edit", methods:["GET","POST"])]
    public function edit(Category $category,Request $request, EntityManagerInterface $entityManagerInterface):Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->flush();

            $this->addFlash("success", "Se edito con exito");
            return $this->redirectToRoute("category_edit",[
                "id"=>$category->getId(),
            ]);
        }

        return $this->render("category/edit.html.twig",[
            "form"=>$form->createView(),
        ]);
    }
}
