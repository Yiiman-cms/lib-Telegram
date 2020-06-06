<?php

namespace system\lib\telegram\Types;

abstract class ArrayOfPhotoSize
{
    public static function fromResponse($data)
    {
        $arrayOfPhotoSize = [];
        foreach ($data as $photoSizeItem) {
            $arrayOfPhotoSize[] = PhotoSize::fromResponse($photoSizeItem);
        }

        return $arrayOfPhotoSize;
    }
}
