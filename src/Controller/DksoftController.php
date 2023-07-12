<?php
namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DksoftController extends AbstractController
{
    private $entityManager;
    private $articleRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    #[Route('/', name: 'home_page')]
    public function website(): Response
    {

        return $this->render('dksoft/welcome.html.twig');
    }

    #[Route('/article', name: 'app_dksoft')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $this->articleRepository->findAll();

        return $this->render('dksoft/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/new', name: 'new_article')]
    public function new(Request $request): Response
    {
    
        $article = new Article();
        $article->setCreatedAt(new \DateTimeImmutable()); 
    
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($article);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('show_article', ['id' => $article->getId()]);
        }
    
        return $this->render('dksoft/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }    

    #[Route('/article/{id}', name: 'show_article')]
public function show(int $id, ArticleRepository $articleRepository): Response
{

    $article = $articleRepository->find($id);

    if (!$article) {
        throw $this->createNotFoundException('Article not found');
    }

    return $this->render('dksoft/show.html.twig', [
        'article' => $article,
    ]);
}

    #[Route('/article/{id}/edit', name: 'edit_article')]
    public function edit(Request $request, int|string $id, EntityManagerInterface $entityManager): Response
    {
        $article = $this->articleRepository->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Add additional logic here, such as updating the article's author.

            $this->entityManager->flush();

            return $this->redirectToRoute('show_article', ['id' => $article->getId()]);
        }

        return $this->render('dksoft/edit.html.twig', [
            'editForm' => $form->createView(),
            'article' => $article,
        ]);
    }
     
      #[Route('/articles/{id}/delete', name:'article_delete')]
      public function deleteArticle($id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

    
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_dksoft');
    }

  }