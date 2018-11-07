<?php

namespace IlyaZelen;

// возвращает размер который помещается в рамку (boundary)
function getBoundaryDimension ($boundaryWidth, $boundaryHeight, $originalWidth, $originalHeight) {
    $newWidth = $originalWidth;
    $newHeight = $originalHeight;

    // сначала проверяем нужно ли масштабировать ширину
    if ($originalWidth > $boundaryWidth) {
        // маштабирует ширину чтобы поместилось
        $newWidth = $boundaryWidth;
        // маштабирует высоту для поддержания пропорции, вычисление зависит от соотношения новой высоты к старой
        $newHeight = $originalHeight * ($newWidth / $originalWidth);
    }

    // проверяет нужно ли масштабировать даже с новой высотой
    if ($newHeight > $boundaryHeight) {
        // маштабирует высоту чтобы поместилось вместо этого
        $newHeight = $boundaryHeight;
        // маштабирует ширину для поддержания пропорции
        $newWidth = $originalWidth * ($newHeight / $originalHeight);
    }

    return [$newWidth, $newHeight];
}

// суфикс вызваемой функции по формату
function getSuffixByFormat ($mimeOrExtension) {
    switch (strtolower($mimeOrExtension)) {
        case 'image/png':
        case 'png':
            return 'png';

        case 'image/jpg':
        case 'image/jpeg':
        case 'jpeg':
        case 'jpg':
            return 'jpeg';

        case 'image/gif':
        case 'gif':
            return 'gif';

        default:
            return null;
    }
}

function getFont ($font, $fonts, $class) {
    if ($font === null) {
        if (!\count($fonts)) {
            throw new \InvalidArgumentException($class::ERROR_NO_FONTS);
        }

        // первый элемент массива
        $font = reset($fonts);
    } else {
        if (!isset($fonts[$font])) {
            throw new \InvalidArgumentException($class::ERROR_NO_FONT_EXISTS);
        }

        $font = $fonts[$font];
    }

    return $font;
}
