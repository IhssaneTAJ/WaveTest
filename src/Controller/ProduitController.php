<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Produit;
use App\Form\ContactType;
use App\Form\ProduitType;
use App\Repository\ContactRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


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
            'produits' => $repo->findAll(),
            'currentMenu' => "produit"
        ]);
    }



    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('produit/home.html.twig', [
            'currentMenu' => "home"
        ]);
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


            //Upload Image
            $file = $form->get('images')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move($this->getParameter('images_directory'), $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $manager = $this->getDoctrine()->getManager();
            $produit->setImages($fileName);

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
    public function show(Produit $produit, HttpFoundationRequest $request, EntityManagerInterface $manager){

        //Formulaire de contact
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);


        //Envoi d'email (not finished)

        if($form->isSubmitted() && $form->isValid()){
            $contact->setProduit($produit->getTitre());

            $manager->persist($contact);
            $manager->flush();


            $this->addFlash('success', 'Votre message a bien été envoyé!');
            return $this->redirectToRoute('produit_show', ['id' => $produit->getId()]);
        }


        //Afficher les détails d'un article
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



    
    /**
     *
     * @Route("/message", name="message")
     */
    public function showMessage(ContactRepository $repo) {
        
        //Selectionner tous les messages
        return $this->render('contact/index.html.twig', [
            'messages' => $repo->findAll(),
            'currentMenu' => "message"

        ]);

    }

}
