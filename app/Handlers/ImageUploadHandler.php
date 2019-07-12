<?php

namespace App\Handlers;

class ImageUploadHandler
{
    //允许的图片格式
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save($file, $folder, $file_prefix)
    {
        //存储的文件夹规则，例如：uploads/images/avatars/201709/21/
        // tips:切割文件夹能让查找效率更高
        $folder_name = "uploads/images/$folder/".date("Ym/d", time());

        //文件具体存储的物理路径， public_path() 获取的是 `public`文件夹的物理路径
        $upload_path = public_path().'/'.$folder_name;

        //获取文件的后缀名 因为图片从粘贴板里面粘贴时后缀为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        //拼接文件名
        $file_name = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;

        //如果不是图片则终止操作
        if (! in_array($extension, $this->allowed_ext)){
            return false;
        }

        //将图片移动到存储路径中
        $file->move($upload_path, $file_name);

        return [
            'path' => config('app.url')."/$folder_name/$file_name"
        ];
    }

}