<?php

namespace News\NewsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Swift_Message;
use Swift_Mailer;

use News\NewsBundle\Fetch\Event\TitleFetchEvent;

/**
 * TitleFetchSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TitleFetchSubscriber implements EventSubscriberInterface
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    
    /**
     * To mail
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
            'news.fetch.title' => 'titleFetch',
        ];
    }
    
    public function titleFetch(TitleFetchEvent $event)
    {
        $this->sendMail(
            $event->getLink(),
            $event->getDescription()
        );
    }
    
    private function sendMail($link, $description)
    {
        $message = Swift_Message::newInstance();
        $message->setSubject('Top news');
        $message->setFrom('wirednewstest@gmail.com');
        $message->setTo($this->reportingMail);
        $body = "{$description} - {$link}";
        $message->setBody($body);
        $this->mailer->send($message);
    }
}
