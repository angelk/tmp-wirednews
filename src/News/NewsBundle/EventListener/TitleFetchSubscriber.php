<?php

namespace News\NewsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use News\NewsBundle\Fetch\Event\TitleFetchEvent;

/**
 * TitleFetchSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TitleFetchSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'news.fetch.title' => 'titleFetch',
        ];
    }
    
    public function titleFetch(TitleFetchEvent $event)
    {
        var_dump($event->getDescription());
        var_dump($event->getLink());
        throw new \Exception('not implemented');
    }
}
