<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(ProduitRepository $repo): Response
    {

        return $this->render('produit/index.html.twig', [
            'produits' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('produit/home.html.twig');
    }

    /**
     * @Route("/produit/new", name="produit_new")
     * @Route("/produit/{id}/edit", name="produit_edit")
     */
    public function form(Produit $produit = null, HttpFoundationRequest $request, EntityManagerInterface $manager){

        if(!$produit){
            $produit = new Produit();
        }


        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute('produit_show', ['id' => $produit->getId()]);

            //return $this->redirectToRoute('produit');
        }

        return $this->render('produit/create.html.twig', [
            'formProduit' => $form->createView(),
            'editMode' => $produit->getId() !== null
        ]);
    }

    /**
     * @Route("/produit/{id}", name="produit_show")
     */
    public function show(Produit $produit){
       return $this->render('produit/show.html.twig', [
            'produit' => $produit
        ]);
    }

    /**
    * @Route("/produit/{id}/delete", name="produit_delete", methods="DELETE")
    */
    public function delete(Produit $produit, EntityManagerInterface $manager){

        $manager->remove($produit);
        $manager->flush();

        return $this->redirectToRoute('produit');
    }

}
