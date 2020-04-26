<?php


namespace App\Service;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageService
{

    private $images_directory;

    private $cacheManager;

    public function __construct($images_directory, CacheManager $cacheManager)
    {
        $this->images_directory = $images_directory;
        $this->cacheManager = $cacheManager;

    }

    public function setImageExtension($file) {
        return $file . '.jpg';
    }

    public function uploadImages(&$imageFiles)
    {
        $images = array();
        foreach ($imageFiles as $imageFile) {
            $newFilename = uniqid();
            $images[] = $newFilename;
            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->images_directory,
                    $this->setImageExtension($newFilename)
                );
            } catch (FileException $e) {
                echo 'Error while uploading a file'; // ... handle exception if something happens during file upload
            }

            // updates the 'newFilename' property to store the jpeg file name
            // instead of its contents

        }
        return $images;
    }

    function moveElement(&$array, $a, $b) {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    function deleteElement(&$array, $a, $b) {
        $file = $array[$a] . '.jpg';
        array_splice($array, $a, $b);
        $this->deleteFile($file);
    }

    public function deleteFile($file)
    {
        $path = 'media/image/' . $file;
        if (file_exists($path)) {
            unlink($path);
            $this->cacheManager->remove($path);
        } else {
            echo 'File not found.';
        }

    }


}
