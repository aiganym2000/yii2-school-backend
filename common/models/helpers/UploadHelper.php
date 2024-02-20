<?php

namespace common\models\helpers;


use Yii;

class UploadHelper
{
    public static function createDir($fileName, $imgPath)
    {
        $path = Yii::getAlias('@static') . '/web/' . $imgPath;
        $ext = self::getExtension($fileName);
        $dir = md5(time() . uniqid()) . $ext;
        $prePath = substr($dir, 0, 5);
        $tempPath = '';
        for ($i = 0; $i < 5; $i++) {
            $tempPath .= '/' . $prePath[$i];
        }
        $tempPath .= '/';
        $dir = uniqid();
        if (!file_exists($path . $tempPath)) {
            mkdir($path . $tempPath, 0775, true);
        }
        return ['dir' => $dir . $ext, 'full_path' => $path, 'path' => $tempPath];
    }

    public static function getExtension($fileName)
    {
        $pos = strrpos($fileName, ".");
        if ($pos !== false) {
            return substr($fileName, $pos);
        } else {
            return "";
        }
    }
}