<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    //Загрузка фотографий в папку images/photo-of-works
    public function upload(UploadedFile $photo, array $photoFileNames)
    {
        $originalFileName = pathinfo($photo->getClientOriginalName())['basename'];

        try {
            if (in_array($originalFileName, $photoFileNames)){
                throw new FileException("<span>{$originalFileName} - уже есть на сайте!</span>");
            }

            $photo->move($this->getTargetDirectory(), $originalFileName);

            echo "<span>{$originalFileName} - успешно загружено!</span>";

        } catch (FileException $ex){
            echo $ex->getMessage();
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}