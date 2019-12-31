<?php
namespace App\Http\Traits;

trait UploadFile
{

    public function handleUploadFile($file, $path = '')
    {
        $name     = md5(time() . 'file_upload');
        $ext      = $file->getClientOriginalExtension();
        $filename = $name . '.' . $ext;

        if (!$path) {
            $path = '/public/uploads/images';
        }

        $file->storeAs($path, $filename);

        return $filename;
    }
}
