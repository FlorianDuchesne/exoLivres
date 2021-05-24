<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivreController extends AbstractController
{

    /**
     * @Route("/livre/delete", name="livre_delete")
     * @Route("/livre/{id}/delete", name="livre_delete")
     */
    public function deleteLivre(Livre $livre = null, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($livre);
        $manager->flush();
        return $this->redirectToRoute('livre');
    }

    /**
     * @Route("/livre/add", name="livre_add")
     * @Route("/livre/{id}/edit", name="livre_edit")
     */
    public function add(Livre $livre = null, Request $request): Response
    {
        if (!$livre) {
            $livre = new Livre();
        }

        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $livre = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('livre');
        }

        return $this->render('livre/add_edit.html.twig', [
            'formAddLivre' => $form->createView(),
            'editMode' => $livre->getId() !== null
        ]);
    }

    /**
     * @Route("/livre", name="livre")
     */
    public function index()
    {
        $livres = $this->getDoctrine()
            ->getRepository(Livre::class)
            ->findBy([], ["titre" => "asc"]);

        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/livre/{id}", name="livre_show", methods = "GET")
     */
    public function findOne($id)
    {
        $livre = $this->getDoctrine()
            ->getRepository(Livre::class)
            ->find($id);

        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }
}
