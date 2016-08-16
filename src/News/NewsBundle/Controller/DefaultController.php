<?php

namespace News\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $titleStatus = $this->getStatus('news.fetch.lastTitle');
        $latestStatus = $this->getStatus(
            'news.fetch.latestNews',
            60*60*24*2
        );
        
        return $this->render(
            'NewsBundle:Default:index.html.twig',
            [
                "latestStatus" => $latestStatus,
                'titleStatus' => $titleStatus,
            ]
        );
    }
    
    /**
     * @param type $cacheKey
     * @param type $outdate
     * @return string
     */
    private function getStatus($cacheKey, $outdate = 60*60*24)
    {
        $cache = $this->get('news.cache');
        /* @var $cache \Symfony\Component\Cache\Adapter\AdapterInterface */
        $cacheItem = $cache->getItem($cacheKey);
        
        if ($cacheItem->isHit()) {
            $lastSuccess = $cacheItem->get();
            if ($lastSuccess > time() - $outdate) {
                $return = "Working";
            } else {
                $return = "Not working";
            }
        } else {
            $return = 'Unknown';
        }

        return $return;
    }
}
