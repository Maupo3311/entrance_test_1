<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /** @var BookRepository */
    private $bookRepository;

    /**
     * @param BookRepository $bookRepository A BookRepository instance.
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("/list", name="book_list")
     *
     * @param Request            $request   A Request instance.
     * @param PaginatorInterface $paginator Service for working with pagination.
     * @return Response
     */
    public function listAction(Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $this->bookRepository->listQuery(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        return $this->render('book/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/create", name="book_create")
     *
     * @param Request $request A Request instance.
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Book successfully created');

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/form.html.twig', [
            'form'   => $form->createView(),
            'action' => 'create',
        ]);
    }

    /**
     * @Route("/show/{id}", name="book_show")
     *
     * @ParamConverter("id", class="App\Entity\Book")
     *
     * @param Book $book A Book instance.
     * @return Response
     */
    public function showAction(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="book_edit")
     *
     * @ParamConverter("id", class="App\Entity\Book")
     *
     * @param Book    $book    A Book instance.
     * @param Request $request A Request instance.
     * @return RedirectResponse|Response
     */
    public function editAction(Book $book, Request $request)
    {
        $form = $this->createForm(BookType::class, $book)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Book edited successfully');

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/form.html.twig', [
            'form'   => $form->createView(),
            'action' => 'edit',
        ]);
    }

    /**
     * @Route("/delete/{id}", name="book_delete")
     *
     * @ParamConverter("id", class="App\Entity\Book")
     *
     * @param Book $book A Book instance.
     * @return RedirectResponse
     */
    public function deleteAction(Book $book): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        $this->addFlash('success', 'Book deleted successfully');

        return $this->redirectToRoute('book_list');
    }
}
