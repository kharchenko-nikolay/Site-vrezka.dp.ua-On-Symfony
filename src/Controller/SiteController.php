<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends AbstractController
{
    public function index()
    {
        return $this->render('site/index.html.twig', []);
    }

    //JSON ответ на AJAX запрос, возвращает все названия изображений в папке с фотографиями
    public function getPhotoNames(Request $request)
    {
        return new JsonResponse([
            'photoNames' => scandir('images/photo-of-works')
        ]);
    }
}