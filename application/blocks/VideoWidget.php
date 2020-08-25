<?php
  class Block_VideoWidget extends Zend_View_Helper_Abstract{
	  public function VideoWidget($value = null){
		 /*  $ns = new Zend_Session_Namespace('language');
		   $lang = $ns->lang;
		   if($ns->lang == ''){
			   $lang = 'vi';
		   }
		   */
		   


		   
		   $registry = Zend_Registry::getInstance();
          $lang = $registry->get('Zend_Locale');
          
          $product = new Product_Model_Product();
          $options= array(
	          'lang' => $lang,
	          'content_type' => 'video',
	          'limit' => 3,
	          'featured' => 1,
          );
          $featured_video = $product->get_details($options);
       
		 //$this->_helper->translate($lang);
		  ?>
		<div class="row">
			       <div class="col-sm-12 col-md-12">
			     <div id="category-news" class="panel panel-default">
    <div class="panel-heading"><?php echo ($lang == 'vi')?'Video nổi bật':'Featured Video'; ?></div>
    <div class="panel-body">
	     <ul class="list-group">
	    <div id="owl-video" class="owl-carousel owl-theme">	
		    <?php foreach($featured_video as $key=>$val):
			    $link = $val['shortDescription'];
			    $video_id = explode("?v=", $link); 
        if (empty($video_id[1])){
           $video_id = explode("/v/", $link); 
           $video_id = explode("&", $video_id[1]); 
           $video_id = $video_id[0];
        }
      
		$thumb_link = "";

        $thumb_link = 'http://img.youtube.com/vi/'.$video_id[1].'/hqdefault.jpg'; 
			     ?>
	<div style="cursor: pointer;" class="item openSlickModal-1" data="<?php echo str_replace('watch?v=', 'embed/', $val['shortDescription']) ?>" onClick="showContent(this);">   
  <li class="list-group-item louis-list-video">
   <span id="playtube" href="#"></span>
	   <img style="width: 100%;" src= "<?=$thumb_link?>"/>
   <p><?php echo $val['name']; ?></p>
  
      </li>
	</div>
				<?php endforeach; ?>
  		
	    </div>
	    </ul>
    </div>
   <!-- <div class="panel-footer">Panel Footer</div>-->
				</div>
			      </div>
			     </div>  
			     
			     
		    <div id="popup-1" class="slickModal">

			<div class="slickWindow">
				<!-- Your popup content -->
		
				<!-- / Your popup content -->
			</div>
		</div>
		
	  <script type="text/javascript">
			function showContent(o)
		{
		
		var url = $(o).attr('data');
		var title = $(o).attr('title');
		
		$(".slickWindow").html('<iframe width="1000" height="620" id="myVideo" src="'+url+'?&autoplay=1" allowfullscreen></iframe>');
		}
		
		
			$(document).ready(function() {
				
				

			    // Modal 1
			    $('#popup-1').slickModals({
			      
			        // Overlay styling
			        overlayBg: true,
			        overlayClosesModal: true,
			        overlayBgColor: 'rgba(0,0,0,0.85)',
			        overlayTransitionSpeed: '0.4',
			        // Background effects
			        pageEffect: 'none',
			        blurBgRadius: '1px',
			        scaleBgValue: '0.9',
			        // Popup styling
			        popupWidth: '80%',
			        popupHeight: '80%',
			        popupLocation: 'center',
			        popupAnimationDuration: '0.6',
			        popupAnimationEffect: 'slideBottom',
			        popupShadowOffsetX: '0',
			        popupShadowOffsetY: '0',
			        popupShadowBlurRadius: '40px',
			        popupShadowSpreadRadius: '0',
			        popupShadowColor: 'rgba(0,0,0,0.4)',
			        popupBackground: '#000',
			        popupRadius: '0',
			        popupMargin: '0',
			        popupPadding: '0',
			        // Responsive rules
			        responsive: true,
			        breakPoint: '480px',
			        mobileLocation: 'center',
			        mobileWidth: '480px',
			        mobileHeight: '280px',
			        mobileRadius: '0',
			        mobileMargin: '0',
			        mobilePadding: '20px',
			        // Animate content
			        contentAnimate: false,
			        contentAnimateEffect: 'zoomIn',
			        contentAnimateSpeed: '0.4',
			        contentAnimateDelay: '0.4',
			        // Youtube videos
			        videoSupport: true,
			        videoAutoPlay: true,
			        videoStopOnClose: true,
			        // Close and reopen button
			        addCloseButton: true,
			        buttonStyle: 'labeled',
			        enableESC: true,
			        reopenClass: 'openSlickModal-1',
			        // Additional events
			        onSlickLoad: function() {
			            console.log("Slick is visible");
			        },
			        onSlickClose: function() {
			            console.log("Slick is hidden");
					$('#myVideo').attr('src', '');
			        }
			    });
			});
		</script>  
			     
		  <?php
		  		  
	  }	  
	} 	
	
	