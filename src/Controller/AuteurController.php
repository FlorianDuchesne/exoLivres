<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Form\AuteurType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{

    /**
     * @Route("/auteur/delete", name="auteur_delete")
     * @Route("/auteur/{id}/delete", name="auteur_delete")
     */
    public function deleteAuteur(Auteur $auteur = null, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($auteur);
        $manager->flush();
        return $this->redirectToRoute('auteur');
    }

    /**
     * @Route("/auteur/add", name="auteur_add")
     * @Route("/auteur/{id}/edit", name="auteur_edit")
     */
    public function add(Auteur $auteur = null, Request $request): Response
    {
        if (!$auteur) {
            $auteur = new Auteur();
        }

        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $auteur = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('auteur');
        }

        return $this->render('auteur/add_edit.html.twig', [
            'formAddAuteur' => $form->createView(),
            'editMode' => $auteur->getId() !== null
        ]);
    }

    /**
     * @Route("/auteur", name="auteur")
     */
    public function index()
    {
        // $auteurs = $this->getDoctrine()
        //     ->getRepository(Auteur::class)
        //     ->getAll();

        $auteurs = $this->getDoctrine()
            ->getRepository(Auteur::class)
            ->findBy([], ["nom" => "asc"]);

        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurs,
        ]);
    }

    /**
     * @Route("/auteur/{id}", name="auteur_show", methods = "GET")
     */
    public function findOne($id)
    {
        $auteur = $this->getDoctrine()
            ->getRepository(Auteur::class)
            ->find($id);
        $livres = $this->getDoctrine()
            ->getRepository(Livre::class)
            ->findBy(["auteur" => $id], ["titre" => "asc"]);

        return $this->render('auteur/show.html.twig', [
            'auteur' => $auteur,
            'livres' => $livres
        ]);


        // Pour info, la version de Mickaël ressemble simplement à ça…
        // (je ne l'ai pas testéé)

        //         /**
        //  * @Route("/{id}", name="auteur_show")
        //  */
        // public function show(Auteur $auteur): Response
        // {
        //     return $this->render('auteur/show.html.twig', [
        //         'auteur' => $auteur
        //     ]);
        // }
    }
}
