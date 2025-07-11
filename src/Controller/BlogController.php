<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Service\FileUploader;
use App\Repository\ArticleRepository;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    private $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->em = $entityManager;
        $this->slugger = $slugger;
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
     * @Route("/categories/{id}", name="show_categories")
     */
    public function category(Category $category, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['category' => $category]);

        return $this->render('blog/category.html.twig', [
            'category' => $category,
            'articlesByCategory' => $articles,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(ArticleRepository $repo): Response
    {
        $popular = $repo->findLatest(3);
        return $this->render('blog/home.html.twig', [
            'popular' => $popular
        ]);
    }
    
    /**
     * @Route("/blog/new", name="new_article")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

  public function addArticle(?Article $article = null, Request $request, EntityManagerInterface $manager, FileUploader $fileuploader) {
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
            $slug = strtolower($this->slugger->slug($article->getTitle()));
            $article->setSlug($slug);
            $file = $article->getImage(); 
            $filename = $file ? $fileuploader->upload($file, $this->getParameter('article_image_directory')) : '';
            $article->setImage($filename);

            //l'auteur de l'article est l'utilisateur connecté
            $article->setUser($this->getUser());

            $manager->persist($article);
            $manager->flush();

                $this->addFlash('success', 'article ajouté');
            return $this->redirectToRoute('blog_show', [
                'slug' => $article->getSlug()
            ]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
                    
    }

    /**
     * @Route("/blog/{slug}", name="blog_show")
     */
    public function showArticle(ArticleRepository $repo, string $slug, Request $request, EntityManagerInterface $manager) {
        $article = $repo->findOneBy(['slug' => $slug]);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Commentaire ajouté');

            return $this->redirectToRoute('blog_show',
             ['slug' => $article->getSlug()]);
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
