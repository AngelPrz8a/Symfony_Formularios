<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * Devuelve a la pagina con todos los post
     */
    #[Route('/', name:"index", methods:["GET"])]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('page/index.html.twig',[
            "posts"=>$entityManagerInterface->getRepository(Post::class)->findAll()
        ]);
    }

    /**
     * Formulario creado en el controlador
     */
    #[Route('/contactos-v1', name:"contact-v1", methods:["GET","POST"])]
    public function contactosV1(Request $request): Response
    {

        $formulario = $this->createFormBuilder()
            ->add("email", TextType::class)
            ->add("message", TextareaType::class,[
                "label"=>"Comentario, suguerencia o mensaje"
            ])
            ->add("save", SubmitType::class,[
                "label"=>"Enviar"
            ])
            //->setMethod("get")
            //->setAction("otra")
            ->getForm()
        ;

        $formulario->handleRequest($request);

        if($formulario->isSubmitted()){
            //getData -> Todos los valores enviados 
            //dd($formulario->getData());
            $this->addFlash("success", "Formulario 1 Exito");
            return $this->redirectToRoute("contact-v1");
        }

        return $this->render('page/contactos-v1.html.twig', [
            'form' => $formulario->createView(),
        ]);
    }


    /**
     *  Formulario llamado de una clase form
     */
    #[Route('/contactos-v2', name:"contact-v2", methods:["GET","POST"])]
    public function contactosV2(Request $request): Response
    {
        $formulario = $this->createForm(ContactType::class);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted()){
            //dd($formulario->getData());
            $this->addFlash("success", "Formulario 2 Exito");
            return $this->redirectToRoute("contact-v2");
        }

        return $this->render('page/contactos-v2.html.twig', [
            'form' => $formulario->createView(),
        ]);
    }
}
