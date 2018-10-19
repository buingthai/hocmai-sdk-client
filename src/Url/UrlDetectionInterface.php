<?php

namespace Hocmai\Url;

interface UrlDetectionInterface
{
    /**
     * Get the currently active URL.
     *
     * @return string
     */
    public function getCurrentUrl();
}
