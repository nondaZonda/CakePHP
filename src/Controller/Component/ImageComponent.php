<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

class ImageComponent extends Component
{

    public function uploadImage($file_name, $image_type)
    {
        $this->imageValidate($file_name);

        // Tworzę ścieżkę do katalogu gdzie będą wrzucane zdjęcia.
        $uploadPath = Configure::read('Api.upload_folders')[$image_type];
        $path = WWW_ROOT . $uploadPath;
        $targetFolder = new Folder($path);
        if ($targetFolder->pwd() === null) {
            $targetFolder = new Folder($path, true, 0755);
        }

        $fileExtension = pathinfo($_FILES[$file_name]['name'], PATHINFO_EXTENSION);
        $targetName = $this->getRandomName($targetFolder, $fileExtension);
        $fileData = file_get_contents($_FILES[$file_name]['tmp_name']);
        $base64 = base64_encode($fileData);

        $result = $this->simpleSaveImg($targetFolder, $targetName, $base64);
        if (!$result){
            throw new Exception('Image save error', 500);
        }
        return  $uploadPath . $targetName;
    }

    private function getRandomName(Folder $folder, $file_sufix)
    {
        $randomName = $this->randomName(8) . '.' . $file_sufix;
        while ($folder->find($randomName)) {
            $randomName = $this->randomName(8);
        }
        return $randomName;
    }

    /**
     * @param $name_length
     * @return array Generuje losową nazwę dla pliku
     */
    private function randomName($name_length)
    {
        $chars = ['abcdefghijklmnopqrstuwxyz', '123456789', 'ABCDEFGHIJKLMOPQRSTUWXYZ'];
        $nameChar = [];
        for ($i = 0; $i < $name_length; $i++) {
            $charSet = $chars[rand(0, 2)];
            $pos = rand(0, (strlen($charSet)-1));
            $nameChar[] = $charSet{$pos};
        }
        return implode('', $nameChar);
    }

    public function simpleSaveImg(Folder $targetFolder, $targetFileName, $base64)
    {
        $path = $targetFolder->pwd() . $targetFileName;
        if (!$targetFolder->find($targetFileName)) {
            $ifp = fopen($path, "w");
            $fwrite = fwrite($ifp, base64_decode($base64));
            if ($fwrite) {
                fclose($ifp);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function simpleDeleteImg($avatarPath)
    {
        if (empty($avatarPath)){
            throw new Exception('Brak avatara', 404);
        }
        $file = new File(WWW_ROOT . $avatarPath);
        if (!$file->exists()) {
            throw new Exception('Plik nie istnieje', 500);
        }
        return $file->delete();
    }

    protected function imageValidate($fileName)
    {
        if (!isset($_FILES[$fileName])) {
            throw new Exception('Nie ma pliku!', 400);
        }
        $imageFile = getimagesize($_FILES[$fileName]['tmp_name']);
        if (!$imageFile) {
            throw new Exception('Nieprawidłowy plik!', 400);
        }
        if ($_FILES[$fileName]['size'] > Configure::read('image.max.size')) {
            throw new Exception('Za duży plik!', 400);
        }
        return true;
    }

}