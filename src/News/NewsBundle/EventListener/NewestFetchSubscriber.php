<?php

namespace News\NewsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Swift_Message;
use Swift_Mailer;

use News\NewsBundle\Fetch\Event\NewestFetchEvent;

/**
 * NewestFetchSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class NewestFetchSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $reportingMail;
    
    /**
     * @param Swift_Mailer $mailer
     * @param type $reportingMail
     */
    public function __construct(Swift_Mailer $mailer, $reportingMail)
    {
        $this->reportingMail = $reportingMail;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'news.fetch.latest' => 'latestFetch',
        ];
    }
    
    public function latestFetch(NewestFetchEvent $event)
    {
        $this->sendMail($event->getPopularArticles());
    }
    
    private function sendMail($news)
    {
        $message = Swift_Message::newInstance();
        $message->setSubject('Latest news');
        $message->setFrom('wirednewstest@gmail.com');
        $message->setTo($this->reportingMail);
        $body = '';
        foreach ($news as $new) {
            $body .= "{$new['link']} - {$new['description']}" . PHP_EOL;
        }
        $message->setBody($body);
        $this->mailer->send($message);
    }
}
