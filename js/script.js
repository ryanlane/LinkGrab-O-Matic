	$(function() {
        $( "#dialog-fetching" ).dialog({
			height: 140,
			modal: true,
            autoOpen: false
		});
        $( "#dialog-postit" ).dialog({
			height: 140,
			modal: true,
            autoOpen: false
		});
        $( ".urlsubmit" ).button();
        $( ".urlsubmit" ).click(function(){
                fetchthedata();
                return false;
            });
        $("#getinfo").submit(function(){
                fetchthedata();
                return false;
            });
        function fetchthedata() {
            
            var preppedUrl = "";
            var preUrl = $('#urlform').val();
            if(!preUrl.indexOf("http://"))
            {
                preppedUrl = preUrl;
            } else {
                 preppedUrl = "http://" + preUrl;
                 $('#urlform').val(preppedUrl);
            }
            $( "#dialog-fetching" ).dialog("open");
            $.post($('#getinfo').attr('action'), { "url": preppedUrl },
                function(data) {
                    $("#textwrapper").empty();
                    $("#imagecollection").empty();           
                    $('.sourceTitle').text(data.page.title);
                    $.each(data.page.images, function(i,item) {                        
                        var imageitemwrap = $("<div/>").addClass("imageItem");
                        var imageitem = $("<img/>").attr("src", item.image);
                        imageitem.attr("width", 120);
                        imageitem.attr("height", "auto");
                        imageitem.appendTo(imageitemwrap);
                        imageitemwrap.appendTo("#imagecollection");
                    });
                    $.each(data.page.paragraphs, function(i,item) {
                        console.log(item.paragraph.length);
                        if(item.paragraph.length > 30)
                        {                   
                            var pstr = item.paragraph.replace(/\&acirc\;/g, "'");
                            console.log(pstr);
                            var paraitemwrap = $("<div/>").addClass("textItem").text(pstr);                        
                            paraitemwrap.appendTo("#textwrapper");
                        }
                    });
                   setupImageSelect();
                   setupTextSelect();
                   $( "#dialog-fetching" ).dialog("close");
                   }
                );
        }
		$( "#check" ).button();
        $('.pagenav').button();
        

        function setupImageSelect() {
            console.log("image Select active");
            $( "#check" ).toggle(function() {
            $( "#imageOnLabel span" ).html("Off");
            $('.imagewrapper').hide();
            $( "#imageOnLabel" ).removeClass("ui-state-active");
            $('#reviewImage').html("");
            },
            function() {
                $( "#imageOnLabel span" ).html("On");
                $('.imagewrapper').show();
                $( "#check" ).addAttr("checked");
            });
            $('.imageItem').click(function() {
               $('.imageItem').removeClass('selectedImage');
                $('.imageItem').css({
                    margin: 2
                    });
                $(this).addClass('selectedImage');
                $(this).css({
                    margin: 0
                    });
                $('#reviewImage').html($(this).html());
                $('#rImage').val($('#reviewImage img').attr('src'));            
            });
        }
        
        function setupTextSelect() {
            $('.textItem').click(function() {
               $('.textItem').removeClass('selectedText');
                $('.textItem').css({
                    margin: 6
                    });
                $(this).addClass('selectedText');
                $(this).css({
                    margin: 4
                    });
                $('#reviewCopy').html($(this).html());
                $('#rCopy').val($(this).html());            
            });
        }
        //setup the tabs
        var $tabs = $( "#wizardharry" ).tabs({
            select: function(event, ui) {
                $('#reviewTitle').html($(".sourceTitle").text());
                $('#rTitle').val($(".sourceTitle").text());
                }
            });

        //wizard navigation button events
        $('.pagenav').click(function() {
            var pagenumber = parseInt($(this).attr("data-page"));
            $tabs.tabs('select', pagenumber); // switch tab            
            return false;
            });

        $('.pagedone').click(function(){
            $('#rUrl').val($('#urlform').val());
            $('#rCopy').val($('#reviewCopy').html());  
            $( "#dialog-postit" ).dialog("open");
            $('#rTitle').val($("#reviewTitle").text());
            $('#finalizePost').submit();
        });
	});