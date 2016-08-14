<?php

namespace News\NewsBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use News\NewsBundle\Fetch\Event\NewestFetchEvent;

class NewsFetchLatestCommand extends NewFetchAbstractCommand
{
    /**
     * cache of news description
     * link => description
     * @var array
     */
    private $newsCache = [];
    
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
        if (!$this->shouldRun($output)) {
            return false;
        }
        
        $crawler = $this->getCrawler('http://www.wired.com/');
        $popularCrawler = $crawler->filter('#most-pop-list li');

        $links = [];
        
        foreach ($popularCrawler as $popularArticle) {
            /* @var $popularArticleCrawler \DOMElement */
            $popularArticleCrawler = new \Symfony\Component\DomCrawler\Crawler($popularArticle);
            $linkCrawler = $popularArticleCrawler->filter('a');
            $link = $linkCrawler->attr('href');
            $titleCrawler = $popularArticleCrawler->filter('h5');
            $title = $titleCrawler->html();
            $description = $this->getNewsDescription($link);
            
            $links[] = [
                'link' => $link,
                'title' => $title,
                'description' => $description,
            ];
        }
        
        $event = new NewestFetchEvent($links);
        $this->dispachEvent('news.fetch.latest', $event);
    }
    
    /**
     * There is no description on main page.
     * Description is fetched form /most-popular page
     * @param string $link
     * @return string
     */
    protected function getNewsDescription($link)
    {
        if (empty($this->newsCache)) {
            $crawler = $this->getCrawler('http://www.wired.com/most-popular');
            $popularCrawler = $crawler->filter('#most-pop-list li');
            foreach ($popularCrawler as $popularArticle) {
                /* @var $popularArticleCrawler \DOMElement */
                $popularArticleCrawler = new \Symfony\Component\DomCrawler\Crawler($popularArticle);
                $linkCrawler = $popularArticleCrawler->filter('a');
                $newsLink = $linkCrawler->attr('href');

                $descriptionCrawler = $popularArticleCrawler->filter('p');
                $description = $descriptionCrawler->text();

                $this->newsCache[$newsLink] = $description;
            }
        }
        
        return $this->newsCache[$link];
    }
    
    /**
     * Determine if command conditions are met
     * @return boolean
     */
    private function shouldRun(OutputInterface $output)
    {
        $now = new \DateTime('now');
        $dateTime2k = new \DateTime('2000-01-01');
        $diff = $now->diff($dateTime2k);
        $diffDays = $diff->format('%a');
        if ($diffDays % 2 != 0) {
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln("Command should run every 2 days");
            }
            return false;
        }
        return true;
    }
}
