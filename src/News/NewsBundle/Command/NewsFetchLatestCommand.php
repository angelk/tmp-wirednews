<?php

namespace News\NewsBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use News\NewsBundle\Fetch\Event\NewestFetchEvent;

class NewsFetchLatestCommand extends NewFetchAbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('news:fetchLatest')
            ->setDescription('Fetch latest news')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = $this->getCrawler('http://www.wired.com/');
        $popularCrawler = $crawler->filter('#most-pop-list li');

        $links = [];
        
        foreach ($popularCrawler as $popularArticle) {
            /* @var $popularArticleCrawler \DOMElement */
            $popularArticleCrawler = new \Symfony\Component\DomCrawler\Crawler($popularArticle);
            $linkCrawler = $popularArticleCrawler->filter('a');
            $link = $linkCrawler->attr('href');
            $descriptionCrawler = $popularArticleCrawler->filter('h5');
            $description = $descriptionCrawler->html();

            $links[] = [
                'link' => $link,
                'description' => $description,
            ];
        }
        
        $event = new NewestFetchEvent($links);
        $this->dispachEvent('news.fetch.newest', $event);
    }
}
