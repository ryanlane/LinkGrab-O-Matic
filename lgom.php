<?php


    ini_set("user_agent","Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.21 (KHTML, like Gecko) Chrome/19.0.1042.0 Safari/535.21");
    ini_set("max_execution_time", 0);
    ini_set("memory_limit", "10000M");
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>LinkGrab-o-matic</title>
        <link type="text/css" href="css/custom-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/styles.css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body>
        <div id="prestep">
            <form method="post" id="getinfo">
                <div class="inputwrapper">
                    <input type="url" name="url" id="urlform" value="<?php echo $_POST['url'] ?>" placeholder="http://www.website.com">
                </div><!-- inputwrapper -->
                <button type="submit" class="urlsubmit">go</button>
            </form>
        </div><!-- prestep -->
        <div style="clear:both;"></div>
        
<?php 
   if (!empty($_POST['url'])) {
    # create and load the HTML
    include('simple_html_dom.php');
    //$context = stream_context_create();
    //stream_context_set_params($context, array('user_agent' => 'UserAgent/1.0');
    

    $html = new simple_html_dom();
    $html->load_file($_POST['url']);
    $scripts = $html->find('script');
    echo $scripts->outertext = '';
    
    //
    $html->find("script")->outertext = "";
    $html->find("comments")->outertext = "";
    # get an element representing the second paragraph
    $title = $html->find("title");
    $images = $html->find("img");

    $bodyitems = $html->find("body text");
    $paragraphs = $bodyitems;

    

    # modify it
    //$element->innertext .= " and we're here to stay.";

    # output it!
    //echo $html->save();
    ?>
    <div id="wizardharry">
        <ul>
            <li><a href="#stepOne">Step 1 - Link Title</a></li>
            <li><a href="#stepTwo">Step 2 - Image</a></li>
            <li><a href="#stepThree">Step 3 - Description</a></li>
            <li><a href="#stepFour">Step 4 - Review</a></li>
	    </ul>
        <div id="stepOne" class="steps">            
            <div class='sourceTitle' contenteditable="true"><?php echo $title[0]->innertext; ?></div>
            <div class="buttons"><a href="#" class="pagenav" data-page="1">next</a></div>
            <div style="clear:both;"></div>
        </div><!-- stepOne -->
    
    
    <div id="stepTwo" class="steps">
        Images are <input type="checkbox" id="check" checked="checked"/><label for="check" id="imageOnLabel">On</label>
        <div class="imagewrapper">
            <p>Pick one image from the <?php   echo count($images) . " found."; ?></p>
    <?php
    foreach ($images as $image)
    {
        $imgpath = $image->src;
        $handle = @fopen($imgpath,'r');
        if($handle !== false)
        {
            $imgwidth = (int)$image->width;
            $imgheight = (int)$image->height;


            //if(($imgwidth > 100) && ($imgheight > 100))
            //{
                (int)$image->width = "auto";
                (int)$image->height = "120";
                echo "<div class='imageItem'>".$image . "</div>";
            //}
        }
    }
    ?>
         <div style="clear:both;"></div>
         </div><!-- imagewrapper -->
        <div class="buttons"><a href="#" class="pagenav" data-page="0">prev</a> <a href="#" class="pagenav" data-page="2">next</a></div>
        <div style="clear:both;"></div>
    </div><!-- stepTwo -->
    <div id="stepThree" class="steps">
        Select some text to use or write your own.
    <div class="textwrapper">
    <?php
    foreach ($paragraphs as $paragraph)
    {
        //$cleantext = preg_replace('#[[(.*?)]]#', '', $paragraph->plaintext);
        //$paragraph->find('comment') '';
        if (strlen($paragraph) > 40) 
        {
        echo "<div class='textItem'>". $paragraph . "</div>";
        }
    }
   }
?>
        </div><!-- textwrapper -->
        <div class="buttons"><a href="#" class="pagenav" data-page="1">prev</a><a href="#" class="pagenav" data-page="3">next</a></div>
        <div style="clear:both;"></div>
        </div><!-- stepThree -->
        
        <div id="stepFour" class="steps">
        <p>Congratulations! Review before you post. Click to edit</p>
        <div id="reviewPost">
            <div id="reviewTitle" contenteditable="true"></div>
            <div id="reviewImage"></div>
            <div id="reviewCopy" contenteditable="true"></div>
        </div>
        <div class="buttons"><a href="#" class="pagenav" data-page="2">prev</a><a href="#" class="pagenav pagedone" >done</a></div>
            <div style="clear:both;"></div>
        </div><!-- stepFour -->
        </div><!-- wizard -->
    </body>
</html>