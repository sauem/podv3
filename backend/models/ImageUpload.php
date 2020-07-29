<?php


namespace backend\models;


use yii\base\Model;

class ImageUpload extends Model
{
    public $billFile;

    public function rules()
    {
        return [
            [['billFile'],'file','skipOnEmpty' => false,'extensions' => 'jpg,jpeg,png,pdf'],

        ];
    }

    function upload(){
        $filePath =  UPLOAD_PATH;

        if($this->validate()){
            $fileName = $filePath .$this->billFile->baseName.'.'.$this->billFile->extension;
            $this->billFile->saveAs($fileName);
            return $fileName;
        }
        return false;
    }
}