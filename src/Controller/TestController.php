<?php 
 
namespace App\Controller;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use App\Services\WebCrawler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    public function list()
    {
        return new Response(
            '<html><body> Hi </body></html>'
        );
    }

    public function crawlTest()
    {
        $uri = "https://www.industrybuying.com/";
        $client = new Client();
        $crawler = $client->request('GET', $uri);
        $sections = $crawler->filter('body')->filter('section');
        $categoryId = "AH_NavigationView";
        /**
         * @var $section \DOMElement
         */
        foreach ($sections as $section)
        {
            if($section->getAttribute('id') == $categoryId)
            {
                for($i = 0; $i < $section->childNodes->length ; $i++)
                {
                    /**
                     * @var $domNode \DOMElement
                     */
                    $domNode = $section->childNodes->item($i);
                    if($domNode->nodeName == "ul")
                    {
                        for($j = 0; $j < $domNode->childNodes->length ; $j++)
                        {
                            $liItem = $domNode->childNodes->item($j);
                            if($liItem->nodeName == "li")
                            {
                                dump($liItem->childNodes->item(1)->textContent);
                            }
                        }
                    }
                }
            }
        }
        dd("done");
    }

    public function crawlCategory(Request $request, WebCrawler $webCrawler)
    {
        $webCrawler->crawlCategories();
    }
}