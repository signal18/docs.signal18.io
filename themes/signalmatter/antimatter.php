<?php
namespace Grav\Theme;

use Grav\Common\Theme;

class Antimatter extends Theme
{
    public function onTwigSiteVariables(): void
    {
        $this->grav["twig"]->twig_vars["db"] = Grav::instance()['database'];
    }
}
