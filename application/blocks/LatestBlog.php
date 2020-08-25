<?php
  class Block_LatestBlog extends Zend_View_Helper_Abstract{
	  public function LatestBlog(){
		  	$pageModel = new Model_Page();
			$select = $pageModel->select();
			$select->where('namespace = ?', 'blog');
			$select->order('id desc');
			$select->limit('10');
			$currentPages = $pageModel->fetchAll($select);
			if($currentPages->count() > 0) {
			$blogs = $currentPages;
			}else{
			$blogs = null;
			}
		  ?>
		<aside class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
		<div id="column-left" class="sidebar">
			<div class="box white latest_blog">
	<div class="box-heading">
		<span>Latest Blog</span>
	</div>
	<div class="box-content">
		<div class="pavblog-latest row">
					<?php foreach($blogs as $blog): ?>
			<div class="col-lg-3 col-md-3 col-sm-3">
				<div class="blog-item">					
					<div class="blog-body">
							<div class="image">
								<img src="/public/images/blog/<?=$blog->photo?>" title="<?=$blog->name?>" alt="<?=$blog->name?>" class="img-responsive">
							</div>
						
						<div class="create-date pull-left">
							<div class="created">
								<span class="day"><?php echo $day = gmdate("d", $blog->date_created); ?></span><hr>
								<span class="month"><?php echo $month = gmdate("m", $blog->date_created); ?></span> 								
							</div>
						</div>
						
						<div class="create-info pull-left">
							<div class="inner">
								<div class="blog-header">
									<h4 class="blog-title">
										<a href="/blog/<?=$blog->name_clean?>" title="<?=$blog->name?>"><?=$blog->name?></a>
									</h4>
								</div>
								<div class="description">
						<?=$blog->description?>
								</div>
							</div>							
						</div>						
						
						<div class="buttons-wrap">
							<a href="/blog/<?=$blog->name_clean?>" class="readmore btn btn-theme-default">Xem thêm</a>
						</div>

					</div>						
				</div>
			</div>
					<? endforeach; ?>			
		</div>
			</div>
	</div>
</div>	
</aside>  
		  
		  <?
		  
	  }	  
	} 	
	
	