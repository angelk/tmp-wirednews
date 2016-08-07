<?php

namespace News\NewsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Description of GenericFetchSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class GenericFetchSubscriber implements EventSubscriberInterface
{
    private $cache;
    
    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'news.fetch.title' => 'titleFetch',
            'news.fetch.latest' => 'latestFetch',
        ];
    }
    
    public function titleFetch()
    {
        $cache = $this->cache->getItem('news.fetch.lastTitle');
        $cache->set(time());
        $this->cache->save($cache);
    }
    
    public function latestFetch()
    {
        $cache = $this->cache->getItem('news.fetch.latestNews');
        $cache->set(time());
        $this->cache->save($cache);
    }
}
