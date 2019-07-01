<?php

namespace App\Controller;

use App\Entity\Files;
use App\Form\FilesType;
use App\Repository\FilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/files")
 */
class FilesController extends AbstractController
{
    /**
     * @Route("/", name="files_index", methods={"GET"})
     */
    public function index(FilesRepository $filesRepository): Response
    {
        return $this->render('files/index.html.twig', [
            'files' => $filesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="files_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $file = new Files();
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $file->getURL();
            $fileName=$this ->generateUniqueFileName().'.'.$url->guessExtension();
            try {
                $url->move(
                    $this->getParameter('brochures_directory'),
                    $fileName
                );
            }
            catch (fileException $e){

            }
            $file->setURL('/uploads/brochures/'.$fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();
            $id_rubrique=$file->getRubrique()->getId();

            return $this->redirectToRoute('show_pdf', ['id' => $id_rubrique]);
        }

        return $this->render('files/new.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="files_show", methods={"GET"})
     */
    public function show(Files $file): Response
    {
        return $this->render('files/show.html.twig', [
            'file' => $file,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="files_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Files $file): Response
    {
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('files_index', [
                'id' => $file->getId(),
            ]);
        }

        return $this->render('files/edit.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{id_rubrique}", name="files_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Files $file,$id_rubrique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($file);
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_pdf', ['id' => $id_rubrique]);
    }
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
}
