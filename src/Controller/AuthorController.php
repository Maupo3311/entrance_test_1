<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AuthorController extends AbstractController
{
    /** @var AuthorRepository */
    private $authorRepository;

    /**
     * @param AuthorRepository $authorRepository An AuthorRepository instance.
     */
    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route("/", name="author_list")
     *
     * @param Request            $request   A Request instance.
     * @param PaginatorInterface $paginator Service for working with pagination.
     * @return Response
     */
    public function listAction(Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $this->authorRepository->listQuery(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        return $this->render('author/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/author/create", name="author_create")
     *
     * @param Request $request A Request instance.
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'Author successfully created');

            return $this->redirectToRoute('author_list');
        }

        return $this->render('author/form.html.twig', [
            'form'   => $form->createView(),
            'action' => 'create',
        ]);
    }

    /**
     * @Route("/author/show/{id}", name="author_show")
     *
     * @ParamConverter("id", class="App\Entity\Author")
     *
     * @param Author $author An Author instance.
     * @return Response
     */
    public function showAction(Author $author): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/author/edit/{id}", name="author_edit")
     *
     * @ParamConverter("id", class="App\Entity\Author")
     *
     * @param Author  $author  An Author instance.
     * @param Request $request A Request instance.
     * @return RedirectResponse|Response
     */
    public function editAction(Author $author, Request $request)
    {
        $form = $this->createForm(AuthorType::class, $author)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'Author edited successfully');

            return $this->redirectToRoute('author_list');
        }

        return $this->render('author/form.html.twig', [
            'form'   => $form->createView(),
            'action' => 'edit',
        ]);
    }

    /**
     * @Route("/author/delete/{id}", name="author_delete")
     *
     * @ParamConverter("id", class="App\Entity\Author")
     *
     * @param Author $author An Author instance.
     * @return RedirectResponse
     */
    public function deleteAction(Author $author): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();

        $this->addFlash('success', 'Author deleted successfully');

        return $this->redirectToRoute('author_list');
    }
}
