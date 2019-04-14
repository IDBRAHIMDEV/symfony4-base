<?php


namespace App\Controller;


use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
   private const POSTS = [
       ['id' => 1, 'title' => 'Formation Angular', 'slug' => 'formation-angular'],
       ['id' => 2, 'title' => 'Formation Symfony', 'slug' => 'formation-Symfony'],
       ['id' => 3, 'title' => 'Formation Laravel', 'slug' => 'formation-Laravel'],
   ];

    /**
     * @Route("/{offset}/{limit}", name="blog_list",
     *        defaults={"offset"=0, "limit"=10},
     *        requirements={"offset"="\d+", "limit": "\d+"},
     *        methods={"GET"}
     * )
     */
   public function list($offset, $limit, Request $request) {

      /* $page = $request->get('page');
       $name = $request->get('name');*/

       $repository = $this->getDoctrine()->getRepository(Post::class);

       $posts = $repository->findAll();

       return $this->json([
           'data' => array_map(function($post) {
               return [
                   'id' => $post->getId(),
                   'label' =>  $post->getTitle(),
                   'slug' => $this->generateUrl('blog_post_by_slug',
                   ['slug' =>  $post->getSlug()])
               ];
           }, $posts),
           'offset' => $offset,
           'limit' => $limit
       ]);
   }

    /**
     * @Route("/post/{id}", name="blog_post_by_id", requirements={"id"="\d+"})
     */
   public function postById($id) {

       $repository = $this->getDoctrine()->getRepository(Post::class);

       $post = $repository->find($id);

        return $this->json(['data' => [
                        'id' => $post->getId(),
                        'label' =>  $post->getTitle(),
                        'slug' => $this->generateUrl('blog_post_by_slug',
                            ['slug' =>  $post->getSlug()])
                    ]
        ]);
   }

    /**
     * @Route("/post/{slug}", name="blog_post_by_slug", methods={"GET"})
     */
   public function postBySlug($slug) {

       $repository = $this->getDoctrine()->getRepository(Post::class);

       $post = $repository->findOneBy(['slug' => $slug]);

       return $this->json(['data' => [
           'id' => $post->getId(),
           'label' =>  $post->getTitle(),
           'slug' => $this->generateUrl('blog_post_by_slug',
               ['slug' =>  $post->getSlug()])
       ]
       ]);
   }



    /**
     * @Route("/post/add", name="blog_post_add",
     *        methods={"POST"})
     */
   public function add(Request $request) {

       $serializer = $this->get('serializer');

       $data = $request->getContent();
       $post = $serializer->deserialize($data, Post::class, 'json');

       $em = $this->getDoctrine()->getManager();

       $em->persist($post);

       $em->flush();

       return $this->json(['data' => $post], Response::HTTP_CREATED);
   }

    /**
     * @Route("/post/delete/{id}", name="blog_delete_post",
     *        methods={"DELETE"}, requirements={"id"="\d+"})
     */
   public function destroy($id) {
       $repository = $this->getDoctrine()->getRepository(Post::class);

       $post = $repository->find($id);

       $em = $this->getDoctrine()->getManager();

       $em->remove($post);

       $em->flush();

       return $this->json(null, Response::HTTP_NO_CONTENT);
   }
}