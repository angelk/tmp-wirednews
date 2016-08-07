<?php

namespace News\NewsBundle\Fetch\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * NewestFetchEvent
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class NewestFetchEvent extends Event
{
    /**
     * @var array
     */
    private $popularArticles;
    
    /**
     * @param string $link
     * @param string $description
     */
    public function __construct($popularArticles)
    {
        $this->popularArticles = $popularArticles;
    }

    /**
     * @return array
     */
    public function getPopularArticles()
    {
        return $this->popularArticles;
    }
}
