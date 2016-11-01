<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('covertosize', array($this, 'coverFunction')),
        );
    }

    public function coverFunction($cover, $width, $height)
    {
        $img = "<img src=\"uploads/images".$cover."\""." width=\"".$width."\" height=\"".$height."\">";
        return $img;
    }

    public function getName()
    {
        return 'app_extension';
    }
}