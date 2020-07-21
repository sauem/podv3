<?php


namespace backend\models;

use yii\base\Model;
class UploadForm extends Model
{
    public $excelFile;
    public function rules()
    {
        return [
            [['excelFile'],'file','skipOnEmpty' => false,'extensions' => 'xlsx,csv']
        ];
    }

    function upload(){
        $filePath =  UPLOAD_PATH;

        if($this->validate()){
            $fileName = $filePath .$this->excelFile->baseName.'.'.$this->excelFile->extension;
            $this->excelFile->saveAs($fileName);
            return $fileName;
        }
        return false;
    }
}
