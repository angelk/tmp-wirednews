<?php

namespace News\NewsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use News\NewsBundle\Fetch\Event\NewestFetchEvent;

/**
 * NewestFetchSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class NewestFetchSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'news.fetch.newest' => 'newestFetch',
        ];
    }
    
    public function newestFetch(NewestFetchEvent $event)
    {
        var_dump($event->getPopularArticles());
        throw new \Exception('not implemented');
    }
}
