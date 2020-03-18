<?php

namespace App\DataFixtures;

use App\Entity\{Website,Brands,Categories};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IndustryBuyingFixtures extends Fixture
{
    public function load(ObjectManager $manager) {

        $websiteName = 'Industry Buying';
        $websiteUrl = 'https://www.industrybuying.com';

        $website = new Website();
        $website->setWebsiteName($websiteName);
        $website->setWebsiteUrl($websiteUrl);
        $manager->persist($website);
        $manager->flush();

        $brandsArray =[
            ['brandName' => '360', 'brandUrl' => 'https://www.industrybuying.com/brands/360/'],
            ['brandName' => 'Alpha', 'brandUrl' => 'https://www.industrybuying.com/brands/alpha/'],
            ['brandName' => 'Green kraft', 'brandUrl' => 'https://www.industrybuying.com/brands/green-kraft/']
        ];

        foreach($brandsArray as $brandArray)
        {
            $brand = new Brands();
            $brand->setBrandName($brandArray['brandName']);
            $brand->setBrandUrl($brandArray['brandUrl']);
            $brand->setWebsite($website);
            $manager->persist($brand);
        }

        $categoriesArray = [
            ['categoryName' => 'Safety', 'categoryUrl' => 'https://www.industrybuying.com/safety-1224/'],
            ['categoryName' => 'Agriculture Garden & Landscaping', 'categoryUrl' => 'https://www.industrybuying.com/agriculture-garden-landscaping-2384/'],
            ['categoryName' => 'Hydraulics', 'categoryUrl' => 'https://www.industrybuying.com/hydraulics-4839/'],
            ['categoryName' => 'Power Tools', 'categoryUrl' => 'https://www.industrybuying.com/power-tools-641/'],
            ['categoryName' => 'Welding', 'categoryUrl' => 'https://www.industrybuying.com/welding-552/'],
            ['categoryName' => 'Office Supplies', 'categoryUrl' => 'https://www.industrybuying.com/office-supplies-3227/'],
            ['categoryName' => 'Testing & Measuring', 'categoryUrl' => 'https://www.industrybuying.com/testing-and-measuring-instruments-2324/'],
            ['categoryName' => 'Electricals', 'categoryUrl' => 'https://www.industrybuying.com/electrical-639/'],
            ['categoryName' => 'Pneumatics', 'categoryUrl' => 'https://www.industrybuying.com/pneumatics-1139/'],
            ['categoryName' => 'Hand Tools', 'categoryUrl' => 'https://www.industrybuying.com/hand-tools-629/'],
            ['categoryName' => 'Material Handling & Packaging', 'categoryUrl' => 'https://www.industrybuying.com/material-handling-and-packaging-1153/'],
            ['categoryName' => "LED's & Lights", 'categoryUrl' => 'https://www.industrybuying.com/led-lights-13759/']
        ];

        foreach($categoriesArray as $categoryArray)
        {
            $category = new Categories();
            $category->setCategoryName($categoryArray['categoryName']);
            $category->setCategoryUrl($categoryArray['categoryUrl']);
            $category->setWebsite($website);
            $manager->persist($category);
        }

        $manager->flush();
    }

}