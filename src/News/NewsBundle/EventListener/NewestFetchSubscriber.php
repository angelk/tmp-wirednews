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
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    
    /**
     * To: mail
     * @var string
     */
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
    
    /**
     * Triggered on 'news.fetch.latest' event
     * @param NewestFetchEvent $event
     */
    public function latestFetch(NewestFetchEvent $event)
    {
        $this->sendMail($event->getPopularArticles());
    }
    
    /**
     * Send mail with given news.
     * @param array $news
     */
    private function sendMail($news)
    {
        $message = Swift_Message::newInstance();
        $message->setSubject('Latest news');
        $message->setFrom('wirednewstest@gmail.com');
        $message->setTo($this->reportingMail);
        $body = '';
        foreach ($news as $new) {
            $body .= "Link: {$new['link']}" . PHP_EOL .
                "Title:{$new['title']}" . PHP_EOL .
                "Descr:{$new['description']}" . PHP_EOL .
                "----" . PHP_EOL;
        }
        $message->setBody($body);
        $this->mailer->send($message);
    }
}
