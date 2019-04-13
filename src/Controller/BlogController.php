<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
   private const POSTS = [
       ['id' => 1, 'title' => 'Formation Angular', 'slug' => 'formation-angular'],
       ['id' => 2, 'title' => 'Formation Symfony', 'slug' => 'formation-Symfony'],
       ['id' => 3, 'title' => 'Formation Laravel', 'slug' => 'formation-Laravel'],
   ];

    /**
     * @Route("/blog/{offset}/{limit}", name="blog_list",
     *        defaults={"offset"=0, "limit"=10},
     *        requirements={"offset"="\d+", "limit": "\d+"},
     *        methods={"GET"}
     * )
     */
   public function list($offset, $limit, Request $request) {

       $page = $request->get('page');
       $name = $request->get('name');
       return new JsonResponse([
           'data' => array_map(function($post) {
               return [
                   'id' => $post['id'] * 100,
                   'label' => $post['title'],
                   'slug' => $this->generateUrl('blog_post_by_slug', ['slug' => $post['slug']])
               ];
           }, self::POSTS),
           'offset' => $offset,
           'limit' => $limit,
           'page' => $page,
           'name' => $name
       ]);
   }

    /**
     * @Route("/blog/post/{id}", name="blog_post_by_id", requirements={"id"="\d+"})
     */
   public function postById($id) {

       $myPost = self::POSTS[array_search($id, array_column(self::POSTS, 'id'))];
        return new JsonResponse(['data' => $myPost]);
   }

    /**
     * @Route("/blog/post/{slug}", name="blog_post_by_slug", methods={"GET"})
     */
   public function postBySlug($slug) {
       $myPost = self::POSTS[array_search($slug, array_column (self::POSTS, 'slug'))];
       return new JsonResponse(['data' => $myPost]);
   }

    /**
     * @Route("/blog/post/add", name="blog_post_add",
     *        methods={"POST"})
     */
   public function add() {

       return new JsonResponse(['data' => 'add']);
   }
}