<?php


namespace App\Controller;


use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends AbstractController
{
    public function index()
    {
        return $this->render('site/index.html.twig', []);
    }

    //JSON ответ на AJAX запрос, возвращает все имена файлов в папке с фотографиями
    public function getPhotoNames()
    {
        return new JsonResponse([
            'photoNames' => scandir('images/photo-of-works')
        ]);
    }

    //Создание и валидация формы для загрузки фотографий в карусель на сайте
    public function buildForm(Request $request, FileUploader $fileUploader)
    {
        //Создание формы для загрузки файлов
        $form = $this->createFormBuilder()
            ->add('photos', FileType::class,
                ['attr' => ['accept' => 'image/*'], 'mapped' => false, 'multiple' => true, 'label' => false])
            ->add('save', SubmitType::class, ['label' => 'Загрузить'])
            ->getForm();

        $form->handleRequest($request);

        //Валидация пришедших данных из формы
        if($form->isSubmitted() && $form->isValid()){

            $photos = $form->get('photos')->getData();
            $photoFileNames = scandir('images/photo-of-works');

            if ($photos){
                foreach ($photos as $photo){
                    $fileUploader->upload($photo, $photoFileNames);
                }
            }
        }

        return $this->render('site/photo-upload.html.twig', [
            'form' => $form->createView()
        ]);
    }
}