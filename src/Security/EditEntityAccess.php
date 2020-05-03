<?php


namespace App\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditEntityAccess extends AbstractController
{
    public function getAccess($user)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()) {
                if ($user->getId() != $this->getUser()->getId()) {
                    throw $this->createAccessDeniedException();
                }
            } else {
                throw $this->createAccessDeniedException();
            }
        }
    }
}
