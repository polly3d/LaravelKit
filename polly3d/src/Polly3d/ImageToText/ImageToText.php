<?php

namespace Polly3d\ImageToText;

class ImageToText
{
    protected $stringMap = [' ','.',',',':',';','o','x','%','#','@'];
    protected $endStr = "\r\n";

    public function toANSIIFrom($path,$everyRowChars = 100)
    {
        $image = $this->_openImage($path);
        //$result = $this->_doTransform($image);
        $result = $this->_doTransform2($image,$everyRowChars);
        imagedestroy($image);
        return $result;
    }

    protected function _doTransform($image)
    {
        $result = '';
        if($image)
        {
            $strLength = count($this->stringMap);
            $width = imagesx($image);
            $height = imagesy($image);
            for($i = 0; $i < $height; $i++)
            {
                for($j = 0; $j < $width; $j++)
                {
                    //获取像素的颜色值，取出rgb三种颜色。
                    //获得灰度值，最直接的办法就(r+g+b)/3，更科学的办法应该是：R * 0.3 + G * 0.59 + B * 0.11
                    //根据灰度值的百分比，获取相应的ANSII字符内容
                    $color = imagecolorat($image,$j,$i);
                    $r = ($color >> 16) & 0xFF;
                    $r += ($color >> 8) & 0xFF;
                    $r += $color & 0xFF;
                    //两次按位取反，能够得到一个整数。这个整数是丢弃小数点部分的整数，如~~1.8的值是1
                    //两次逻辑取反，能够得到一个bool值。如果!!(123)，得到结果true
                    $index = ~~ ($r * $strLength / 768);
                    $result .= $this->stringMap[$strLength - 1 - $index];
                }
                $result .= $this->endStr;
            }
            $result =  "<pre style='font-family: Courier New;font-size: 10px; line-height: 8px;'>" . $result . "</pre>";
        }
        return $result;
    }

    protected function _doTransform2($image,$everyRowChars= 100)
    {
        $result = '';
        $strLength = count($this->stringMap);
        if($image)
        {
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);

            $everyRowChars = $imageWidth < $everyRowChars ? $imageWidth : $everyRowChars;
            //根据宽进行等比例缩放
            $scaleRate = ~~ ($imageWidth / $everyRowChars);
            $rows = ~~ ($imageHeight / $scaleRate);
            $cols = ~~ ($imageWidth / $scaleRate);

            for($i = 0; $i < $rows; $i++)
            {
                for($j = 0; $j < $cols; $j++)
                {
                    $x = $j * $scaleRate;
                    $y = $i * $scaleRate;
                    $index = $this->_getAverageIntensity($image,$x,$y,$scaleRate);
                    $result .= $this->stringMap[$strLength - 1 - $index];
                }
                $result .= $this->endStr;
            }
            $result =  "<pre style='font-family: Courier New;font-size: 10px; line-height: 8px;'>" . $result . "</pre>";
        }
        return $result;
    }

    /**
     * 图块某个区域的平均灰色强度
     * @param $image
     * @param $x
     * @param $y
     * @param $scaleRate
     */
    protected function _getAverageIntensity($image,$x,$y,$scaleRate)
    {
        $width = $scaleRate;
        $height = $scaleRate;
        $totalStrength = 0;
        $strLength = count($this->stringMap);
        for($i = 0; $i < $height; $i++)
        {
            for($j = 0; $j < $width; $j++)
            {
                $color = imagecolorat($image,$x + $j,$y + $i);
                $r = ($color >> 16) & 0xFF;
                $r += ($color >> 8) & 0xFF;
                $r += $color & 0xFF;
                $temp = ~~ ($r * $strLength / 768);
                $totalStrength += $temp;
            }
        }
        $index = ~~ ($totalStrength / ($width * $height));
        return $index;
    }

    protected function _openImage($path)
    {
        $fileExtension = $this->_getFileExtention($path);
        $openFun = 'imagecreatefrom' . $fileExtension;
        try
        {
            $image = $openFun($path);
        }catch(\Exception $error)
        {
            echo '打开图片出错，请重试';
            exit;
        }
        return $image;
    }

    protected function _getFileExtention($url)
    {
        $fileExtension = '';
        if(strpos($url,'jpg') || strpos($url,'jpeg'))
        {
            $fileExtension = 'jpeg';
        }
        else if(strpos($url,'png'))
        {
            $fileExtension = 'png';
        }
        return $fileExtension;
    }


}
