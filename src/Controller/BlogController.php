<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BlogController.php',
        ]);
    }


    /**
     * @Route("/post/{id}", requirements={"id": "\d+"}, name="get-blog-by-id", methods={"GET"})
     */
    public function postById($id){
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
        );
    }

    /**
     * @Route("/post/{slug}", name="get-blog-by-slug", methods={"GET"})
     */
    public function postBySlug($slug){
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->findBy(['slug' => $slug])
        );
    }
    
    /**
     * @Route("/add", name="blog-add", methods={"POST"})
     */
    public function add(Request $request){

        $serializer = $this->get('serializer');
        $post = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->json($post);
    }

    /**
     * @Route("/post/{id}/delete", name="blog-delete", methods={"DELETE"})
     */
    public function delete($id){

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(BlogPost::class)->find($id);
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, 204);
    }

}
