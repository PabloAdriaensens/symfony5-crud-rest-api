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

    #[Route('/api/update_api/{id}', name: 'update_api', methods: ['PUT'])]
    public function update_api(Request $request, $id): Response
    {
        $data = $this->em->getRepository(Crud::class)->find($id);
        $parameter = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $data->setTitle($parameter['title']);
        $data->setContent($parameter['content']);

        $this->em->persist($data);
        $this->em->flush();

        return $this->json('Updated Successfully!');
    }

    #[Route('/api/delete_api/{id}', name: 'delete_api', methods: ['DELETE'])]
    public function delete_api($id): Response
    {
        $data = $this->em->getRepository(Crud::class)->find($id);

        $this->em->remove($data);
        $this->em->flush();

        return $this->json('Deleted Successfully!');
    }

    #[Route('/api/fetchall_api', name: 'fetchall_api', methods: ['GET'])]
    public function fetchall_api(): Response
    {
        $data = $this->em->getRepository(Crud::class)->findAll();

        $res = [];
        foreach ($data as $d) {
            $res[] = [
                'id' => $d->getId(),
                'title' => $d->getTitle(),
                'content' => $d->getId(),
            ];
        }

        return $this->json($res);
    }
}
