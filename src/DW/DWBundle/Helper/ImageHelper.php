<?php

namespace DW\DWBundle\Helper;

use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHelper
{
    private $dataManager;
    private $filterManager;
    private $rootDir;

    public function __construct(DataManager $dataManager, FilterManager $filterManager,
                                $rootDir)
    {
        $this->dataManager = $dataManager;
        $this->filterManager = $filterManager;
        $this->rootDir = $rootDir;
    }

    public function uploadFile(UploadedFile $file, $imageCache, $destination, $filename = null)
    {
        $tmpFolderPathAbs = $this->rootDir . '/../web/' . Directories::IMAGE_TMP;
        if ($filename == null) {
            $filename = sha1(uniqid(mt_rand(), true));
        }
        $tmpImageName = $filename . '.' . $file->guessExtension();
        $file->move($tmpFolderPathAbs, $tmpImageName);
        $tmpImagePathRel = '/' . Directories::IMAGE_TMP . $tmpImageName;

        $processedImage = $this->dataManager->find($imageCache, $tmpImagePathRel);
        $response = $this->filterManager->applyFilter($processedImage, $imageCache);
        $avatar = $response->getContent();
        unlink($tmpFolderPathAbs . $tmpImageName); // eliminate unfiltered temp file.
        $permanentFolderPath = $this->rootDir . '/../web/' . $destination;
        $permanentImagePath = $permanentFolderPath . $tmpImageName;
        $f = fopen($permanentImagePath, 'w');
        fwrite($f, $avatar);
        fclose($f);

        return $tmpImageName;
    }

    public function getImageFromUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $headers = get_headers($url);
            $responseCode = substr($headers[0], 9, 3);
            if($responseCode != "404") {
                $contents = @file_get_contents($url);
                if ($contents != false) {
                    if (strpos($url, "?")) {
                        $urlArray = explode("?", $url, 2);
                        $url = $urlArray[0];
                    }
                    $filename = sha1(uniqid(mt_rand(), true));
                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                    $tmpImageName = $filename . '.' . $ext;
                    $tmpImagePathRel = 'uploads/tmp/' . $tmpImageName;
                    file_put_contents($tmpImagePathRel, $contents);

                    $processedImage = $this->dataManager->find('avatar200', $tmpImagePathRel);
                    $response = $this->filterManager->applyFilter($processedImage, 'avatar200');
                    $avatar = $response->getContent();
                    unlink($tmpImagePathRel); // eliminate unfiltered temp file.
                    $permanentFolderPath = 'uploads/images/avatar/';
                    $permanentImagePath = $permanentFolderPath . $tmpImageName;
                    $f = fopen($permanentImagePath, 'w');
                    fwrite($f, $avatar);
                    fclose($f);

                    return $tmpImageName;
                }
            }
        }
    }

}