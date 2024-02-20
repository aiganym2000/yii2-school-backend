<?php


namespace common\models\helpers;


use yii\web\ConflictHttpException;
use yii\web\UploadedFile;

class ImageHelper
{
    public static function getVideo($name, $path)
    {
        $returnPath = null;
        $size = null;

        $file = UploadedFile::getInstanceByName($name);
        if ($file) {
            if ($file->type == 'video/mp4') {
                $dir = UploadHelper::createDir($file, $path);
                $filePath = $dir['full_path'] . $dir['path'] . $dir['dir'];
                $file->saveAs($filePath);
                $returnPath = $dir['path'] . $dir['dir'];
                $size = $file->size;
            } else {
                throw new ConflictHttpException('Файл должен иметь формат mp4');
            }
        }

        return ['path' => $returnPath, 'size' => $size];
    }

    public static function getImage($name, $path)
    {
        $returnPath = null;
        $size = null;

        $file = UploadedFile::getInstanceByName($name);
        if ($file) {
            if ($file->size <= 1048576) {
                if ($file->type == 'image/png' || $file->type == 'image/jpeg' || $file->type == 'image/jpg' || $file->type == 'image/gif') {
                    $dir = UploadHelper::createDir($file, $path);
                    $filePath = $dir['full_path'] . $dir['path'] . $dir['dir'];
                    $file->saveAs($filePath);
                    $returnPath = $dir['path'] . $dir['dir'];
                    $size = $file->size;
                } else {
                    throw new ConflictHttpException('Файл должен иметь формат png, jpg или gif');
                }
            } else {
                throw new ConflictHttpException('Файл должен весить не более 1 МБ');
            }
        }

        return ['path' => $returnPath, 'size' => $size];
    }
}