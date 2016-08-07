<?php

namespace News\NewsBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewsFetchTitleCommand extends NewFetchAbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('news:fetchTitle')
            ->setDescription('Fetch top article data')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = $this->getCrawler('http://www.wired.com/');
        $titleCrawler = $crawler->filter('#p1 h2');
        $title = $titleCrawler->html();
        
        $linkCrawler = $crawler->filter('#p1 a');
        $link = $linkCrawler->attr('href');
        
        $titleFetchEvent = new \News\NewsBundle\Fetch\Event\TitleFetchEvent($link, $title);
        $this->dispachEvent('news.fetch.title', $titleFetchEvent);
    }
}
