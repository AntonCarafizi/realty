<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeItemService extends AbstractController
{

    public function likeItem($item_id)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $getLikedItems = $userRepository->getLikedItems();
        array_push($getLikedItems, $item_id);
        $userRepository->setLikedItems($getLikedItems);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    }

    public function unlikeItem($item_id)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        if ($userRepository) {
            $getLikedItems = $userRepository->getLikedItems();
            $key = array_search($item_id, $getLikedItems);
            array_splice($getLikedItems, $key, 1);
            $userRepository->setLikedItems($getLikedItems);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }
    }
}
