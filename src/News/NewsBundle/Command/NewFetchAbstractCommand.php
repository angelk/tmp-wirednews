<?php

namespace News\NewsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Symfony\Component\DomCrawler\Crawler;

/**
 * NewFetchAbstractCommand
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
abstract class NewFetchAbstractCommand extends ContainerAwareCommand
{
    private $eventDispahcer;
    
    public function setEventDispacher(EventDispatcherInterface $dispacher)
    {
        $this->eventDispahcer = $dispacher;
    }
    
    protected function dispachEvent($eventName, $event)
    {
        if ($this->eventDispahcer) {
            $this->eventDispahcer->dispatch($eventName, $event);
        }
    }
    
    /**
     * @param string $url
     * @return Crawler
     */
    protected function getCrawler($url)
    {
        $guzzle = new \GuzzleHttp\Client();
        $result = $guzzle->get($url);
        $resultBody = $result->getBody();
        $crawler = new Crawler($resultBody->getContents());
        return $crawler;
    }
}
