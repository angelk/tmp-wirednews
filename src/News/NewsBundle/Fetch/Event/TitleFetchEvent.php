<?php

namespace News\NewsBundle\Fetch\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * TitleFetchEvent
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TitleFetchEvent extends Event
{
    
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $link;
    
    /**
     * @param string $link
     * @param string $description
     */
    public function __construct($link, $description)
    {
        $this->link = $link;
        $this->description = $description;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
