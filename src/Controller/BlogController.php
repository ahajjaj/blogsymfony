<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Service\FileUploader;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/categories", name="show_categories")
     */
    public function category(CategoryRepository $repo, int $id): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $category = $repo->findAll();

        $articles = $repo->findByCategory($id);

        $category = $articles->getCategory();
        
        return $this->render('blog/category.html.twig', [
            'controller_name' => 'BlogController',
            'categories' => $category,
            'articlesByCategory' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('blog/home.html.twig');
    }
    
    /**
     * @Route("/blog/new", name="new_article")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function addArticle(Article $article = null, Request $request, EntityManagerInterface $manager, FileUploader $fileuploader) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if(!$article){
            $article = new Article();
        }

        /*$form =$this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->getForm(); */

        $form =$this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()) {
                $article->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            }
            $file = $article->getImage(); 
            $filename = $file ? $fileuploader->upload($file, $this->getParameter('article_image_directory')) : '';
            $article->setImage($filename);
            //l'auteur de l'article est l'utilisateur connecté
            $article->setUser($this->getUser());

            $manager->persist($article);
            $manager->flush();

	        $this->addFlash('success', 'article ajouté');
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
                    
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function showArticle(Article $article, Request $request, EntityManagerInterface $manager) {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        //$article = $repo->find($id);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show',
             ['id' => $article->getId()]);
        }
        return $this->render('blog/show.html.twig', [
            'article'=> $article,
            'formComment'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/article/modifier/{id}", name="edit_admin" )
     */
    public function modifArticle(Request $request, Article $article){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // t o  b e  c o n t i n u e d ...
    }
}
