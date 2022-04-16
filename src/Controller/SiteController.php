<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function buildForm(Request $request)
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

                    $originalFileName = pathinfo($photo->getClientOriginalName())['basename'];

                    try {
                        if (in_array($originalFileName, $photoFileNames)){
                            throw new FileException(
                                "<span>{$originalFileName} - уже есть на сайте!</span>");
                        }

                        $photo->move($this->getParameter('photos_directory'), $originalFileName);

                        echo "<span>{$originalFileName} - успешно загружено!</span>";

                    } catch (FileException $ex){
                        echo $ex->getMessage();
                    }
                }
            }
        }

        return $this->render('site/photo-upload.html.twig', [
            'form' => $form->createView()
        ]);
    }
}