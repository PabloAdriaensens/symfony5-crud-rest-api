<?php

namespace App\Controller;

use App\Entity\Crud;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    #[Route('/api/post_api', name: 'post_api', methods: ['POST'])]
    public function post_api(Request $request): Response
    {
        $crud = new Crud();
        $parameter = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $crud->setTitle($parameter['title']);
        $crud->setContent($parameter['content']);

        $this->em->persist($crud);
        $this->em->flush();

        return $this->json('Inserted Successfully!');
    }
}
