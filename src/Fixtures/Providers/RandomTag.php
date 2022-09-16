<?php

namespace App\Fixtures\Providers;

class RandomTag
{
    public function randomTag(): string
    {
        $tag = [
            'github',
            'npm',
            'swiper',
            'vue.js',
            'react',
            'angular.js',
            'visualCode',
            'doctrine',
            'vichUpload',
            'webDesign',
        ];

        return $tag[array_rand($tag)];
    }
}
