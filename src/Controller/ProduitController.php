<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Produit;
use App\Form\ContactType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\Session;


class ProduitController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/produit", name="produit")
     */
    public function index(ProduitRepository $repo): Response
    {
        //Selectionner tous les articles
        return $this->render('produit/index.html.twig', [
            'produits' => $repo->findAll()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('produit/home.html.twig');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/produit/new", name="produit_new")
     * @Route("/produit/{id}/edit", name="produit_edit")
     */
    public function form(Produit $produit = null, HttpFoundationRequest $request, EntityManagerInterface $manager){

        if(!$produit){
            $produit = new Produit();
        }


        //Créer ou Modifier un article
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($produit);
            $manager->flush();

            $this->addFlash('success', 'Données enregistrées avec succès!');

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
    public function show(Produit $produit, HttpFoundationRequest $request){

        //Formulaire de contact
        $contact = new Contact();
        $contact->setProduit($produit);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);


        //Envoi d'email (not finished)

        // if($form->isSubmitted() && $form->isValid()){
        //     //$notification->notify($contact);
        //     $this->addFlash('success', 'Votre message a bien été envoyé!');
        //     return $this->redirectToRoute('produit_show', ['id' => $produit->getId()]);
        // }


        //Afficher la description d'un article
       return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/produit/{id}/delete", name="produit_delete", methods="DELETE")
    */
    public function delete(Produit $produit, EntityManagerInterface $manager){

        //Supprimer un article
        $manager->remove($produit);
        $manager->flush();

        $this->addFlash('warning', 'Produit supprimé avec succès!');

        return $this->redirectToRoute('produit');
    }

}
