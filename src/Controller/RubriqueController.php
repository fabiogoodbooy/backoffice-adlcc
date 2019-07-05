<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Rubrique;
use App\Form\RubriqueType;
use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rubrique")
 */
class RubriqueController extends AbstractController
{
    /**
     * @Route("/", name="rubrique_index", methods={"GET"})
     */
    public function index(RubriqueRepository $rubriqueRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('rubrique/index.html.twig', [
            'rubriques' => $rubriqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="rubrique_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $rubrique = new Rubrique();
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rubrique);
            $entityManager->flush();

            return $this->redirectToRoute('rubrique_index');
        }

        return $this->render('rubrique/new.html.twig', [
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rubrique_show", methods={"GET"})
     */
    public function show(Rubrique $rubrique): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('rubrique/show.html.twig', [
            'rubrique' => $rubrique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rubrique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rubrique $rubrique): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rubrique_index', [
                'id' => $rubrique->getId(),
            ]);
        }

        return $this->render('rubrique/edit.html.twig', [
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rubrique_delete")
     */
    public function delete(Request $request, Rubrique $rubrique ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isCsrfTokenValid('delete'.$rubrique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rubrique);
            $entityManager->flush();
        }
        return $this->redirectToRoute('rubrique_index');
    }
    /**
     * @Route("/{id}/pdf", name="show_pdf")
     */
    public function show_pdf($id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repository = $this->getDoctrine()->getManager()->getRepository(Rubrique::class);
        $rubrique = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $file=$em->getRepository(Files::class)->findBy(array('rubrique'=>$rubrique));
        return $this->render('rubrique/show_pdf.html.twig', array(
            'rubrique'=>$rubrique,
            'files'=> $file
        ));
    }
}
