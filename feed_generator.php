<?php
    require 'app/Mage.php';
    Mage::app('admin');
    $resource = Mage::getSingleton('core/resource');
    $conn = $resource->getConnection('core_write');

    $start_time = date('h:i:s');
        
    $total=0;
    $json_out = [];
    $store_name = "Romagnollo";
    $store_dir = "/data/romagnollo.com.br/";
    $store_url = "https://www.romagnollo.com.br/";
    $store_filename = "romagnollo"; //only base without extension

    echo "\n Start Time: ".$start_time;

    file_put_contents($store_dir.$store_filename."_tmp.json",'{ "products" : [');
    
    $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');

    $xml = new SimpleXMLElement('<rss xmlns:g="http://base.google.com/ns/1.0" />');
    $xml->addAttribute('version', '2.0');
    $xml->addChild('channel');
    $xml->channel->addChild('title','Lista de produtos '.$store_name);
    $xml->channel->addChild('link',$store_url);
    $xml->channel->addChild('description','Detalhes dos produtos vendidos na loja - '.time());

    foreach ($collection as $product){   
        if ($product->getStatus() != '1') continue; //status = 1 -> product enable

        $categoryIds = $product->getCategoryIds();
        $categoriesNames = [];
        foreach ($categoryIds as $key) {
            $category = Mage::getModel('catalog/category')->load($key) ;
            $categoriesNames[] = $category->getName();
        }

        $mediaConfig = Mage::getModel('catalog/product_media_config');
        $imageUrl = $mediaConfig->getMediaUrl($product->getImage());

        //if image not exist in product the product will be ignored
        if ((strpos($imageUrl,'.png') === false) && 
            (strpos($imageUrl,'.jpg') === false) && 
            (strpos($imageUrl,'.jpeg') === false) && 
            (strpos($imageUrl,'.webp') === false)) continue;
           
        $special_price = $product->getSpecialPrice()>0?$product->getSpecialPrice():$product->getPrice();
        if ($special_price > 0) {
            $sale_price = $product->getSpecialPrice();
        }
        else {
            $sale_price = $product->getPrice();
        }
        
        $quantity = (int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId())->getQty();

        // echo "Processing ".$product->getName()." - SKU: ".$product->getSku()."\n";

        // ![CDATA[ conjunto blusa manga curta com short tam6 6 ]]
        $description = preg_replace( "/\r|\n/", "",strip_tags($product->getDescription()));
        $description = '<![CDATA[ '.substr($description,0,4999).' ]]>';

        $title = strip_tags(substr($product->getName(),0,150));
        $title = '<![CDATA[ '.$title.' ]]>';
        
        $weight = str_replace('.',',',(String)round(($product->getWeight()/1000),5));

        $item = [];
        $item['id'] = $product->getSku();
        $item['sku'] = $product->getSku();
        $item['mpn'] = $product->getGtin();
        $item['gtin'] = $product->getGtin();
        $item['title'] = $title;
        $item['description'] = $description;
        $item['sale_price'] = str_replace('.',',',round($sale_price,2)).' BRL';
        $item['price'] = str_replace('.',',',round($product->getPrice(),2)).' BRL';
        $item['sale_price'] = str_replace('.',',',round($product->getPrice(),2)).' BRL';
        $item['categories_ids'] = implode($product->getCategoryIds());
        $item['categories_names'] = implode(' > ',$categoriesNames);
        $item['link'] = $store_url.$product->getUrlPath();
        $item['absolute_url'] = str_replace('feed_generator.php/','',$product->getProductUrl());
        $item['image_link'] = $imageUrl;
        $item['product_type'] = implode(' > ',$categoriesNames);
        $item['condition'] = 'new';
        $item['availability'] = ($quantity>0)?'in stock':'out of stock';
        $item['brand'] = $store_name;
        $item['stock'] = intval($quantity);
        $item['weight'] = $weight;
        $item['condition'] = 'new';
        $item['brand'] = 'Beatriz Folheados';
        $item['color'] = (strpos(strtolower($title),'prata') === false)?'gold':'prata';
        $item['gender'] = 'unissex';
        $item['size'] = '';
        $item['material'] = (strpos(strtolower($title),'prata') === false)?'folheado':'prata 925';
        $item['google_product_category'] = 'Roupas e acessórios > Joias';
        
        if ($item['price'] == '0 BRL') continue;

        // if ($pass_first === false) {
        //     $pass_first = true;
        // }
        // else {
        //     file_put_contents($store_dir.$store_filename."_tmp.json",",\n", FILE_APPEND);
        // }
        file_put_contents($store_dir.$store_filename."_tmp.json",json_encode($item,JSON_PRETTY_PRINT), FILE_APPEND);
        
        $sub_xml = $xml->channel->addChild('item');
        to_xml($sub_xml, $item);   

        $total++;
    }
    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $out_xml = html_entity_decode(trim($dom->saveXML()));

    file_put_contents($store_dir.$store_filename.".xml",$out_xml);

    file_put_contents($store_dir.$store_filename."_tmp.json","]}",FILE_APPEND);   
    if (file_exists($store_dir.'/'.$store_filename.'.json')) unlink($store_dir.'/'.$store_filename.'.json');
    rename($store_dir.$store_filename.'_tmp.json',$store_dir.'/'.$store_filename.'.json');
    
    echo ' -> End Time: '.date('h:i:s')."\n\n";
    
    function to_xml(SimpleXMLElement $object, array $data){   
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                to_xml($new_object, $value);
            } else {
                // if the key is an integer, it needs text with it to actually work.
                if (is_int($key) === true) {
                    $key = "key_$key";
                }
    
                $object->addChild($key, $value, "http://base.google.com/ns/1.0");
            }   
        }   
    }
