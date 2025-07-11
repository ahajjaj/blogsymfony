<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccountController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profile(AuthorizationCheckerInterface $auth): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        return $this->render('account/profile.html.twig', [
            'user' => $user
        ]);
    }
}
