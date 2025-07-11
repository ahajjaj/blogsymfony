<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * Liste des users du site
     * 
     * @Route("/users", name="users")
     */
    public function usersList(UserRepository $repo){

        $users = $repo->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * Modifier l'utilisateur
     * 
     * @Route("/user/edit/{id}", name="edit_user")
     */
    public function editUser(Request $request, User $user) {

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Utilisateur modifié avec succès.');

            return $this->redirectToRoute('admin_users');
        }
        return $this->render('admin/userEdit.html.twig', [
            'userForm' => $form->createView()
            ]);
    }

    /**
     * Liste des articles
     *
     * @Route("/articles", name="articles")
     */
    public function articlesList(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('admin/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * Supprimer un article
     *
     * @Route("/article/delete/{id}", name="delete_article")
     */
    public function deleteArticle(Article $article, EntityManagerInterface $em): Response
    {
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', 'Article supprimé');

        return $this->redirectToRoute('admin_articles');
    }
}
