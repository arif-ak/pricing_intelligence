<?php

namespace App\Services;

use App\Entity\Brands;
use App\Entity\Categories;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Templating\EngineInterface;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class WebCrawler
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * WebCrawler constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->targetUrl = '';
    }

    public function crawlIndustryBuyingCategories($dataObject)
    {
        $this->targetUrl = $dataObject->getCategoryUrl();
        $client = new Client();
        $crawler = $client->request('GET', $this->targetUrl);

        $subCategoryArray = [];

        if($dataObject->getWebsite()->getWebsiteName() == 'Industry Buying')
        {
            $subCategoryList = $crawler->filter('.catethumb .productTitle a')->each(function($node) {
                $subCategoryUrl = $node->attr('href');
                
                return $subCategoryUrl;
            });


            foreach($subCategoryList as $subCategoryUrl)
                $this->crawlIndustryBuyingPage($dataObject->getWebsite()->getWebsiteUrl() . $subCategoryUrl);
                // array_push($subCategoryArray,$this->crawlPage($dataObject->getWebsite()->getWebsiteUrl() . $subCategoryUrl));

            return new Response('success');

        } else {
            $response = $dataObject->getWebsite()->getWebsiteUrl(). ' has not been configured for crawling. Please configure from backend.';
            return new Response($response);
        }

        
    }

    public function crawlBrands($dataObject)
    {
        $this->targetUrl = $dataObject->getBrandUrl();
        $client = new Client();
        $crawler = $client->request('GET', $this->targetUrl);
        $productsCount = $crawler->filter('.AH_ProductView')->count();

        if($productsCount <= 0) //Check if products exist
        {
            $status = false; //status flag
            $response = "No products found in this target page :" . $this->targetUrl;

            return new Response($response);
        }

        if($dataObject->getWebsite()->getWebsiteName() == 'Industry Buying')
        {
            $response = $this->crawlIndustryBuyingPage($this->targetUrl);

            return new Response('success');

        } else {

            $response = $dataObject->getWebsite()->getWebsiteUrl(). ' has not been configured for crawling. Please configure from backend.';
            return new Response($response);

        }
        
    }

    public function crawlCategoryAndBrand($retrieveDataObj)
    {
        $response = "success";

        // Constructing url for brand and category search : start
        // Sample url - https://www.industrybuying.com/agriculture-garden-landscaping-2384/brand-alpha/

        $brandString = str_replace(' ', '-', strtolower($retrieveDataObj->getBrand()->getBrandName()));
        $url = $retrieveDataObj->getCategory()->getCategoryUrl() . 'brand-' . $brandString . '/';
        $this->targetUrl = $url;

        // Constructing url for brand and category search : end

        if($retrieveDataObj->getBrand()->getWebsite()->getWebsiteName() == 'Industry Buying')
        {
            $client = new Client();
            $crawler = $client->request('GET', $this->targetUrl);
            $productsCount = $crawler->filter('.AH_ProductView')->count();
            
            if($crawler->getBaseHref() != $this->targetUrl) // Check for error in input url
            {
                $response = "Url changed(non existent category/brand)";

                return new Response($response);
            }

            if($productsCount <= 0) //Check if products exist
            {
                $response = $response = "No products found in this target page: " . $this->targetUrl;

                return new Response($response);
            }

            $this->crawlIndustryBuyingPage($this->targetUrl);

            return new Response($response);

        } else {

            $response = $retrieveDataObj->getBrand()->getWebsite()->getWebsiteName() . ' has not been configured for crawling. Please configure from backend.';
            return new Response($response);
            
        }
    }

    public function crawlIndustryBuyingPage($uri)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        // $uri = "https://www.industrybuying.com/agriculture-garden-landscaping-2384/brand-360/";
        $client = new Client();
        
        // check for paginated results in target url : start

        $paginationCrawler = $client->request('GET',$uri);
        $pagesCount = $paginationCrawler->filter('#AH_BottomPaginationView .pagiButtons .pagination li')->count();
        $pagesCount = $pagesCount ? $pagesCount : 1; //$pagesCount returns 0 when products are not paginated, hence setting manually to one

        // check for paginated results in target url : end

        for($i = 1; $i <= $pagesCount; $i++) //running crawler for each paginated page
        {
            $crawler = $client->request('GET', $uri . '?page=' . $i);
            
            $this->entityManager->clear();
            
            $crawler->filter('.AH_ProductView .proColBox')->each(function($node) {               

                // declaring string array keys to empty
                $productArray['productUrl'] = $productArray['productName'] = $productArray['brandName'] 
                = $productArray['similarProductLink'] = $productArray['productImageUrl'] = $productArray['moq'] = ''; 

                 //declaring float array keys to 0
                $productArray['productMrp'] = $productArray['productPrice'] = $productArray['productPackingQuantity']
                = $productArray['productDiscount'] = 0;

                $productArray['productUrl']  = $node->filter('.prFeatureName')->attr('href'); 
                $productArray['productName']  = $node->filter('.prFeatureName')->text();
                $productArray['brandName'] = $node->filter('.brand')->text();
                $productArray['similarProductLink'] = $node->filter('.by a')->attr('href');
                $productArray['productImageUrl'] = $node->filter('.proPicImg a .AH_LazyLoadImg')->attr('data-original');

                if($node->filter('.proPriceBox .pro-upto-wide del')->count())
                    $productArray['productMrp'] = trim($node->filter('.proPriceBox .pro-upto-wide del')->text());

                $productArray['productMrp'] = (float) filter_var($productArray['productMrp'], FILTER_SANITIZE_NUMBER_INT);

                if($node->filter('.proPriceBox .pro-upto-wide .rs')->count()){

                    // Incoming string format 'Rs. 5555 / piece' , logic to separate price and quantity : start
                    $productPricePerPiece = trim(preg_replace('/\s\s+/', ' ', $node->filter('.proPriceBox .pro-upto-wide .rs')->text()));
                    $productPricePerPieceArray = explode('/',$productPricePerPiece);
                    $productArray['productPrice'] = (float) filter_var($productPricePerPieceArray[0], FILTER_SANITIZE_NUMBER_INT);

                    if(isset($productPricePerPieceArray[1]))
                    {
                        $productArray['productPackingQuantity'] = (int) filter_var($productPricePerPieceArray[1], FILTER_SANITIZE_NUMBER_INT);
                        $productArray['productPackingQuantity'] = $productArray['productPackingQuantity'] ? $productArray['productPackingQuantity'] : 1;
                    } 
                    // Incoming string format 'Rs. 5555 / piece' , logic to separate price and quantity : end

                }

                if($node->filter('.moqDetail')->count()){
                    $productArray['moq'] = trim(preg_replace('/\s\s+/', ' ', $node->filter('.moqDetail')->text()));
                    // $productArray['moq'] = (int) filter_var($node->filter('.moqDetail')->text(), FILTER_SANITIZE_NUMBER_INT);
                }


                if($node->filter('.proPriceBox .heighlight-discount .right')->count()){
                    $productDiscountText = $node->filter('.proPriceBox .heighlight-discount .right')->text();
                    $productArray['productDiscount'] =  (float) filter_var($productDiscountText, FILTER_SANITIZE_NUMBER_INT);
                }

                $product = new Product();
                $product->setProductName($productArray['productName']);
                $product->setProductUrl($productArray['productUrl']);
                $product->setProductPrice($productArray['productPrice']);
                $product->setProductMrp($productArray['productMrp']);
                $product->setProductDiscount($productArray['productDiscount']);
                $product->setPackingQuantity($productArray['productPackingQuantity']);
                $product->setMoq($productArray['moq']);
                $product->setBrand($productArray['brandName']);
                $product->setProductImageUrl($productArray['productImageUrl']);
                $product->setSimilarProductsLink($productArray['similarProductLink']);
                $product->setDataSourceUrl($this->targetUrl);
                
                $this->entityManager->persist($product);

                // if(($counter % $batch) === 0){
                //     $this->entityManager->flush();  
                //     $this->entityManager->clear();
                // }

                });

                $this->entityManager->flush();
                $this->entityManager->clear();
        }

    }

}