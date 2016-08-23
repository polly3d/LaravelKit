<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Storage;
use Polly3d\ImageToText\ImageToText;

class ImageToTextController extends Controller
{
    public function index()
    {
        //显示两种输入形式
        //1、上传图片
        //2、通过URL地址生成
        return view('image2txt.index');
    }

    public function toANSIIByUpload(Request $request,ImageToText $imageToText)
    {
        $fileType = ['jpg','png','jpeg'];
        if($request->hasFile('pic'))
        {
            $fileExtension = $request->file('pic')->getClientOriginalExtension();
            if(in_array($fileExtension,$fileType))
            {
                $fileName = 'temp_' . time() . '.' . $fileExtension;
                $request->file('pic')->move(storage_path('upload_temp'),$fileName);
                $result = $imageToText->toANSIIFrom(storage_path('upload_temp'). DIRECTORY_SEPARATOR . $fileName);
                return $result;
            }
        }

        return '请上传文件';
    }

    public function toANSIIByURL(Request $request, ImageToText $imageToText)
    {
        $url = '';
        if($url = $request->input('url'))
        {
            $result = $imageToText->toANSIIFrom($url);
            return $result;
        }

        return '请输入URL';
    }
}
