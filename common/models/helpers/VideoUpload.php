<?php

namespace common\models\helpers;

use vova07\fileapi\actions\UploadAction as FileAPIUpload;

class VideoUpload extends FileAPIUpload
{
    public $uploadOnlyImage = false;

    private $_validator = 'video';
}