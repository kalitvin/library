<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('covertosize', [$this, 'coverFunction'], [
                'is_safe' => ['html']
            ]),
        );

    }

    public function coverFunction($cover, $width, $height)
    {
        $img=sprintf('<img src="uploads/images%s" width="%s" height="%s">', $cover, $width, $height);
        return $img;
    }

    public function getName()
    {
        return 'app_extension';
    }

}