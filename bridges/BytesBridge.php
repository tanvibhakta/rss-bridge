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
                
        $maxArticles = 5;
        $count = 0;

        foreach($site->find('li') as $article) {
            
            if ($count >= $maxArticles) break;
            
            $item = [];

            // Get the link to the full article
            $linkElement = $article->find('a', 0);
            if (!$linkElement) continue;  // Skip if no link found
            $postUrl = "https://bytes.dev$linkElement->href";
            $item['uri'] = $postUrl;
           

            // Extract the title
            $titleElement = $article->find('h3 div', 0);
            $item['title'] = $titleElement ? $titleElement->plaintext : 'No title';

            $fullArticle = getSimpleHTMLDOM($postUrl);
            if ($fullArticle) {
                $contentElement = $fullArticle->find('div.hidden + div', 0);
                $item['content'] = $contentElement;
            }
            
            // Extract the publication date
            $dateElement = $article->find('article div span', 0);
            $item['timestamp'] = $dateElement->innertext;

            $this->items[] = $item;
            $count++;
        }
    }

    public function getIcon()
    {
        return 'https://bytes.dev/favicon/favicon-32x32.png';
    }
}
