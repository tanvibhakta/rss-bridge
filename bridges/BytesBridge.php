<?php
class BytesBridge extends BridgeAbstract
{

    const NAME =  "Bytes Bridge";
    const URI = "https://bytes.dev/archives";
    const DESCRIPTION = "Returns the latest issue of the bytes.dev newsletter";
    const MAINTAINER  = "tanvibhakta";


    public function collectData()
    {        
        $site = getSimpleHTMLDOM(self::URI);
                
        $maxArticles = 20;
        $count = 0;

        foreach($site->find('li') as $article) {
            
            if ($count >= $maxArticles) break;
            
            $item = [];

            // Get the link to the full article
            $linkElement = $article->find('a', 0);
            if (!$linkElement) continue;  // Skip if no link found
            $item['uri'] = "https://bytes.dev$linkElement->href";
           

            // Extract the title
            $titleElement = $article->find('h3 div', 0);
            $item['title'] = $titleElement ? $titleElement->plaintext : 'No title';


            // Add the item to the feed
            $this->items[] = $item;
        }
    }

    public function getIcon()
    {
        return 'https://bytes.dev/favicon/favicon-32x32.png';
    }
}
