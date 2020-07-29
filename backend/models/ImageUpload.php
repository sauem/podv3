<?php


namespace backend\models;


use yii\base\Model;

class ImageUpload extends Model
{
    public $billFile;

    public function rules()
    {
        return [
            [['billFile'],'file','skipOnEmpty' => false,'extensions' => 'jpg,jpeg,png,pdf','maxFiles' => 4],

        ];
    }

    function upload(){
        $filePath =  UPLOAD_PATH;
        $paths = [];
        if($this->validate()){
            foreach ($this->billFile as $k => $file){
                $fileName = $filePath. $file->baseName. '.'. $file->extension;
                if($file->saveAs($fileName)){
                    $name = $file->baseName. '.'. $file->extension;
                    $paths[$k] = $name;

                    $imgModel = new OrdersBilling;
                    $imgModel->path = $name;
                    $imgModel->active = OrdersBilling::DRAFT;
                    $imgModel->save();
                }
            }
            return $paths;
        }
        return false;
    }
}