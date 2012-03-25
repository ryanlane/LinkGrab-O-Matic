<?php
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-type: application/json');
    ini_set("user_agent","Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.21 (KHTML, like Gecko) Chrome/19.0.1042.0 Safari/535.21");
    ini_set("max_execution_time", 0);
    ini_set("memory_limit", "10000M");
    ini_set('display_errors','1');

    
    $currenturl = "";
    $isposted = false;
    if (!empty($_POST))
    {
        $currenturl = $_POST['url'];
        $isposted = true;
    }
    
    if ($isposted) {    
        include('simple_html_dom.php');
        $htmldom = new simple_html_dom();        
        $htmldom->load_file($currenturl);          

        $title = $htmldom->find('title');
  
        echo nl2br('{ "page" : ');
        echo nl2br('{ "title" : "'. utf8_encode(trim($title[0]->innertext)) .'",');
        
        $images = $htmldom->find("img");
        $bodyitems = $htmldom->find("body p text");
        $paragraphs = $bodyitems;
       
        echo nl2br('"images" : [');
        $numImages = count($images);
        $icount = 0;

        foreach ($images as $image)
        {
            if($icount+1 == $numImages)
            {
                $cditem = "";
            }
            else
            {
                $cditem = ",";
            }
            $imgpath = $image->src;
            $handle = @fopen($imgpath,'r');
            if($handle !== false)
            {  
                echo nl2br('{ "image" : "'. $imgpath .'"}'. $cditem);
            }
            else
            {
                $parse = parse_url($currenturl);
                $host = $parse['host'];
                if($imgpath[0] == "/")
                {   echo nl2br('{ "image" : "http://'. $host . $imgpath .'"}'. $cditem); }
                else
                {
                    echo nl2br('{ "image" : "'. $currenturl . '/' . $imgpath .'"}'. $cditem);
                }
            }
            $icount++;
        }
         echo '],'; //close images
        
        echo ' "paragraphs" : [';
        $numPs = count($paragraphs);
        $iPs = 0;
        foreach ($paragraphs as $paragraph)
        {
            if($iPs+1 == $numPs)
            {
                $cditem = "";
            }
            else
            {
                $cditem = ",";
            }
            //if (strlen($paragraph) > 40) 
            //{
                $search = array('{', '}',"\r\n", "\n", "\r", "[", "]", "(", ")", "--", "!--");
                $replace = '';
                $cleanp = str_replace($search, $replace, trim($paragraph->innertext));
                $cleanp = str_replace('\'','&#39;',$cleanp);
                $cleanp = preg_replace('/\s+/', ' ', $cleanp);
                
                echo '{ "paragraph" : "'. utf8_encode(htmlentities($cleanp)) . '"}'. $cditem;           
           // }
            $iPs++;
        }
        echo ']'; //close paragraphs
        echo '}'; //close page items
        echo '}'; //close json
    }
?>

