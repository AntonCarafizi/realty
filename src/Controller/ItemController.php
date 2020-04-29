<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ImageService;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/item",  name="item_")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('item/index.html.twig');
    }

    /**
     * @Route("/search/filter/{filter}/{page}", name="search", methods={"GET"}, defaults={"page"="1"}, requirements={"page"="\d+"})
     * @param Request $request
     * @param ItemRepository $itemRepository
     * @param PaginatorInterface $paginator
     * @param $filter
     * @param $page
     * @return Response
     */
    public function search(Request $request, ItemRepository $itemRepository, PaginatorInterface $paginator, $filter, $page): Response
    {
        $user = $this->getUser();
        $now = new \DateTime("now");
        $filterSet = [
            'sale' => ['is_for_rent' => 0],
            'rent' => ['is_for_rent' => 1],
            'user' => ['user' => $user],
            'house' => ['type' => 1],
            'apartment' => ['type' => 0],
            'garage' => ['type' => 2],
            'new' => ['created' => $now]
        ];
        $criteria = [];
        if ($filter) {
            foreach ($filterSet as $key => $value) {
                if ($filter == $key) {
                    $criteria = $value;
                }
            }
        }

        $query = (!empty($criteria)) ? $itemRepository->findBy($criteria) : $itemRepository->findAll();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page)/*page number*/,
            3/*limit per page*/
        );
        $pagination->setParam('q', null);

        return $this->render('item/search.html.twig', [
            'items' => $pagination,
        ]);
    }


    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, AuthenticationUtils $authenticationUtils, ImageService $imageService): Response
    {
        $item = new Item();
        $user = $this->getUser();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('images')->getData();
            $images = $imageService->uploadImages($imageFiles);
            $item->setImages($images);
            $item->setCreated(new \DateTime("now"));
            $user->addItem($item);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Item $item, ImageService $imageService): Response
    {

        if ($this->getUser()) {
            if ($item->getUser()->getId() != $this->getUser()->getId()) {
                return $this->redirectToRoute('app_login');
            }
        }

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        $item_images = $item->getImages();

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('images')->getData();
            $form_images = $imageService->uploadImages($imageFiles);
            $images = array_merge($item_images, $form_images);

            if ($request->get('image_delete') !== null) {
                if ($this->isCsrfTokenValid('delete_image' . $item->getId(), $request->request->get('_token'))) {
                    $imageService->deleteElement($images, $request->get('image_delete'), 1);
                }
            }

            if ($request->get('image_main') !== null) {
                $imageService->moveElement($images, $request->get('image_main'), 0);
            }

            if ($request->get('image_move_up') !== null and $request->get('image_move_up') !== "0") {
                $imageService->moveElement($images, $request->get('image_move_up'), $request->get('image_move_up') - 1);
            }

            if ($request->get('image_move_down') !== null) {
                $imageService->moveElement($images, $request->get('image_move_down'), $request->get('image_move_down') + 1);
            }

            $item->setImages($images);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('item_show', ['id' => $item->getId()]);
        }


        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Item $item, ImageService $imageService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $images = $item->getImages();
            $images = array_map([$imageService, 'setImageExtension'], $images);
            array_map([$imageService, 'deleteFile'], $images);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index');
    }

}
