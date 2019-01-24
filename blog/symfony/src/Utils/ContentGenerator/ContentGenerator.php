<?php

namespace App\Utils\ContentGenerator;

use App\Utils\Contracts\ContentGenerator\ContentGeneratorInterface;

class ContentGenerator implements ContentGeneratorInterface
{
    /**
     * @inheritdoc
     */
    public function getRealContent(string $type): ?string
    {
        switch ($type) {
            case 'title':
                return substr(
                    strip_tags(
                        file_get_contents('https://loripsum.net/api/1/short')
                    )
                    , 0, 100);

            case 'text':
                return file_get_contents('https://loripsum.net/api/' . random_int(10, 20));

            case 'textShort':
                return substr(
                    file_get_contents('https://loripsum.net/api/' . random_int(1, 2) . '/short')
                    , 0, 255);

            default:
                throw new \Error("Please, use one of 'title', 'text' or 'textShort'.");
        }
    }
}
