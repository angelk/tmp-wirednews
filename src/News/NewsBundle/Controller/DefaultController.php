<?php

namespace News\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $titleStatus = $this->getStatus('news.fetch.lastTitle');
        $latestStatus = $this->getStatus('news.fetch.latestNews');
        
        return $this->render(
            'NewsBundle:Default:index.html.twig',
            [
                "latestStatus" => $latestStatus,
                'titleStatus' => $titleStatus,
            ]
        );
    }
    
    private function getStatus($cacheKey)
    {
        $cache = $this->get('news.cache');
        /* @var $cache \Symfony\Component\Cache\Adapter\AdapterInterface */
        $cacheItem = $cache->getItem($cacheKey);
        
        if ($cacheItem->isHit()) {
            $lastSuccess = $cacheItem->get();
            if ($lastSuccess > time() - 60*60*24) {
                $return = "Working";
            } else {
                $return = "Now working";
            }
        } else {
            $return = 'Unknown';
        }

        return $return;
    }
}
