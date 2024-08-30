<?php if($post->status == 'visible' || $post->status == 'null'): ?>
<div class="card mb-4" data-post-id="<?php echo e($post->id); ?>">
	<div class="row px-4 pt-4 pb-1">
		<div class="col-3 col-sm-3 col-md-3 col-lg-2">
			<div class="profilePicSmall mt-0 ml-0 mr-2 mb-2">
				<a href="<?php echo e($post->profile->url); ?>">
					<img src="<?php echo e(secure_image($post->profile->profilePic, 95, 95)); ?>" alt="" class="img-fluid">
				</a>
			</div>
		</div>
		
		<div class="col-9 col-sm-9 col-md-9 col-lg-10">
			<div class="mt-1 clearfix">

			<div class="float-left">
				<span class="post-username <?php if($post->profile->isVerified == 'Yes'): ?> post-user-badge <?php endif; ?>"><?php echo e($post->profile->name); ?></span>
				<br>
				<a href="<?php echo e($post->profile->url); ?>" class="post-handle-url">
					<?php echo e($post->profile->handle); ?>

				</a>
				<br>
                
                <?php if($post->profile->is_live == 1): ?>
                <a href="/join-live?playback_url=<?php echo e(urlencode($post->profile->playback_url)); ?>&post_id=<?php echo e($post->id); ?>" class="btn btn-danger">
                    Join Live
                </a>
                
                <?php endif; ?>
				<span class="d-block d-sm-block d-md-none">
					<span class="text-muted">
					<small>
                        <i class="fas fa-clock mr-1"></i> <?php echo e(\Carbon\Carbon::parse($post->created_at)->format('g:i:s A - m d, Y')); ?>

					</small>
					<br>
					 <?php if($post->media_type == 'Video'): ?>
                        <span class="text-muted">
                            <small>
                                 <i class="fas fa-eye mr-1"></i> <span id="views-count-<?php echo e($post->id); ?>"><?php echo e($post->views); ?></span> Views
                            </small>
                        </span>
                    <?php endif; ?>
				</span>
			</div>

			<div class="float-right ">

				<div class="float-left pt-2 d-none d-sm-none d-md-block">
					<span class="text-muted">
					<small>
                        <i class="fas fa-clock mr-1"></i> <?php echo e(\Carbon\Carbon::parse($post->created_at)->format('g:i:s A - m d, Y')); ?>

					</small>
					  <?php if($post->media_type == 'Video'): ?>
                        <span class="text-muted">
                            <small>
                                 <i class="fas fa-eye mr-1"></i> <span id="views-count-<?php echo e($post->id); ?>"><?php echo e($post->views); ?></span> Views
                            </small>
                        </span>
                    <?php endif; ?>
				</div>

          
				<div class="dropdown dropleft float-right">
					<a href="" class="btn text-secondary dropdown-toggle postActionsDropdown" data-toggle="dropdown" id="dropdown-<?php echo e($post->id); ?>" aria-haspopup="true" aria-expanded="false">
						<h2 class="d-inline m-0 p-0"><i class="fas fa-ellipsis-h"></i></h2>
					</a>
					<div class="dropdown-menu" aria-labelledby="dropdown-<?php echo e($post->id); ?>">
						<?php if( $post->isCreator() ): ?>
							<a class="dropdown-item" href="<?php echo e(route('editPost', ['post' => $post->id])); ?>"><?php echo app('translator')->get('post.editPost'); ?></a>
							<a class="dropdown-item delete-post" href="<?php echo e(route('deletePost', ['post' => $post])); ?>" data-id="<?php echo e($post->id); ?>"><?php echo app('translator')->get('post.deletePost'); ?></a>
						<?php endif; ?>
						<a class="dropdown-item copyLink" href="javascript:void(0);" data-clipboard-text="<?php echo e($post->slug); ?>">
							<?php echo app('translator')->get( 'post.copyLink' ); ?>
						</a>
						<a class="dropdown-item" href="<?php echo e($post->slug); ?>">
							<?php echo app('translator')->get( 'post.postLink' ); ?>
						</a>
					</div>
				</div>
				
			</span>
          

			</div>
		<div class="float-right mx-5">
    <?php if($post->is_Posted == 1): ?>
        <span>republished</span>
        <a href="<?php echo e($post->profile_handle); ?>" class="post-handle-url">
            <?php echo e('@' . $post->profile_handle); ?>

        </a>
    <?php endif; ?>
</div>
			</div>
		</div>

	</div>

<?php if( $post->userHasAccess() ): ?> 
<div class="pl-4 pr-4 pt-0 pb-2 text-333">
    <div class="text-content" id="text-content-<?php echo $post->id; ?>">
        <?php echo clean(turnLinksIntoAtags(
            preg_replace_callback(
                '/(@[\w\-]+|#\w+)/',
                function($matches) {
                    $match = $matches[0];
                    if ($match[0] === '@') {
                        $username = substr($match, 1);
                        return "<a href='/{$username}'>{$match}</a>";
                    } elseif ($match[0] === '#') {
                        $hashtag = substr($match, 1);
                        return "<a href='/hashtags/{$hashtag}'>{$match}</a>";
                    }
                },
                nl2br($post->text_content) // Removed 'e()' function
            )
        ), 'youtube'); ?>

    </div>
    <button class="read-more" id="read-more-<?php echo $post->id; ?>" onclick="toggleText(<?php echo $post->id; ?>)" style="display: none;">Read More</button>
</div>
	<?php echo $__env->make('posts.post-media', ['post' => $post], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


	<div class="border-top px-3 py-3">
		<h4 class="d-inline">
			<a href="<?php if(auth()->check()): ?> javascript:void(0); <?php else: ?> <?php echo e(route( 'login' )); ?> <?php endif; ?>" class="noHover postLike <?php if(auth()->check()): ?> lovePost <?php endif; ?> <?php if( auth()->check() && auth()->user()->hasLiked( $post )): ?> d-none <?php endif; ?>" data-id="<?php echo e($post->id); ?>">
				<i class="far fa-heart"></i> <span class="post-likes-count" data-id="<?php echo e($post->id); ?>"><?php echo e($post->likes->count()); ?></span>
			</a>
		<a href="javascript:void(0);"
   class="noHover postLike unlovePost <?php if( (auth()->check() && !auth()->user()->hasLiked( $post )) OR !auth()->check() ): ?> d-none <?php endif; ?>"
   data-id="<?php echo e($post->id); ?>">
   <i class="fas fa-heart"></i> <span class="post-likes-count" data-id="<?php echo e($post->id); ?>"><?php echo e($post->likes->count()); ?></span>
</a>

		</h4>		
		&nbsp;&nbsp;

		<h4 class="d-inline">
		<a href="<?php if(auth()->check()): ?> javascript:void(0); <?php else: ?> <?php echo e(route( 'login' )); ?> <?php endif; ?>" class="text-secondary noHover <?php if(auth()->check()): ?> loadComments <?php endif; ?>" data-id="<?php echo e($post->id); ?>">
			<i class="far fa-comments"></i> <span class="post-comments-count" data-id="<?php echo e($post->id); ?>"><?php echo e($post->comments->count()); ?></span>
		</a>
		</h4>
		&nbsp;&nbsp;

		<?php echo $__env->make('tips.tip-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			
			<h4 class="d-inline">
            <form action="<?php echo e(route('repostPost', ['post' => $post->id])); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                        <img alt="zip icon" class="post-uploader-icons zip-icon" src="/svg/Republish.svg">
                    </button>
                </form>
		</h4>

		<div class="leave-comment mt-3 mb-2 d-none" data-id="<?php echo e($post->id); ?>">

			<input type="text" name="new-comment-<?php echo e($post->id); ?>" class="form-control leave-comment-inp" placeholder="<?php echo app('translator')->get('post.writeCommentAndPressEnter'); ?>" required="required" data-id="<?php echo e($post->id); ?>">

		</div>

		<div class="post-<?php echo e($post->id); ?>-lastId d-none"></div>

 <div class="appendComments d-none" data-id="<?php echo e($post->id); ?>">
    <?php echo clean(turnLinksIntoAtags(
        preg_replace_callback(
            '/(@\w+|#\w+)/',
            function($matches) {
                $match = $matches[0];
                if ($match[0] === '@') {
                    $username = substr($match, 1);
                    return "<a href='/{$username}'>{$match}</a>";
                } elseif ($match[0] === '#') {
                    $hashtag = substr($match, 1);
                    return "<a href='/hashtags/{$hashtag}'>{$match}</a>";
                }
            },
            nl2br(
                preg_replace_callback(
                    '/<iframe[^>]*src="https:\/\/www\.youtube\.com\/embed\/([a-zA-Z0-9_-]+)"[^>]*><\/iframe>/',
                    function($matches) {
                        return $matches[0]; // Return the iframe HTML
                    },
                    $post->content
                )
            )
        )
    )); ?>

</div>



		<?php if( $post->comments->count() > opt( 'commentsPerPost', 5 ) ): ?>
		<a class="loadMoreComments d-none" href="javascript:void(0);" data-id="<?php echo e($post->id); ?>">
			<?php echo app('translator')->get( 'post.loadMoreComments' ); ?>
		</a>

		<div class="noMoreComments d-none text-secondary" data-id="<?php echo e($post->id); ?>">
			<i class="fas fa-exclamation-triangle"></i> <?php echo app('translator')->get( 'post.noMoreComments' ); ?>
		</div>
		<?php endif; ?>

	</div>

<?php else: ?>
	

<div class="pt-1 pb-2 pl-3">
    <?php if($post->media_type != 'None'): ?>
        <!--<?php echo clean($post->text_content); ?>-->
        
        <?php
            // Regular expression to match words starting with '#'
            $pattern = '/#(\w+)/';
            
            // Function to replace hashtags with links
            $processedText = preg_replace_callback($pattern, function($matches) {
                $tag = $matches[1];
                return "<a href='/hashtags/{$tag}'>#{$tag}</a>";
            }, $post->text_content);
        ?>
        
        <?php echo $processedText; ?>

        
    <?php endif; ?>
</div>



<?php
    // Define the text content with the iframe for demonstration purposes
    $textContent = $post->text_content;

    // Check if the text content contains an iframe
    $containsIframe = preg_match('/<iframe.*?src=["\'](.*?)["\'].*?>.*?<\/iframe>/', $textContent) ? 'Yes' : 'No';
?>

	<div class="locked-post p-5 text-center text-secondary">
		<br><br>
		<h1 class="display-2">
		    <?php if($containsIframe === 'Yes'): ?>
    <!-- Display video icon if iframe is detected -->
    <img alt="video icon" class="post-uploader-icons video-icon" src="/svg/video-new-icon.svg" style="width:100px; height:100px"
    <?php endif; ?>
		<?php if($post->media_type == 'None'): ?>
			  <img v-tooltip="writing" alt="writing icon" class="post-uploader-icons writing-icon"
                     src="/svg/Writing.svg" style="width:100px; height:100px">
		<?php elseif($post->media_type == 'Image'): ?>
			 <img alt="photo icon" class="post-uploader-icons photo-icon" src="/svg/Upload-Picture.svg" style="width:100px; height:100px">
		<?php elseif($post->media_type == 'Video'): ?>
			<img alt="video icon" class="post-uploader-icons video-icon" src="/svg/video-new-icon.svg" style="width:100px; height:100px">
		<?php elseif($post->media_type == 'Audio'): ?>
			                <img v-tooltip="audioUploadTranslated" alt="audio icon" class="post-uploader-icons audio-icon" style="width:100px; height:100px">

		<?php elseif($post->media_type == 'ZIP'): ?>
			                 <img v-tooltip="zipUploadTranslated" alt="zip icon" class="post-uploader-icons zip-icon"
                     src="/svg/zip-upload.svg" style="width:100px; height:100px">

		<?php endif; ?>
		</h1>

		                <img alt="locked icon" class="post-uploader-icons lock-closed-icon" src="/svg/Locked.svg">
 <?php echo app('translator')->get('post.locked'); ?>

		<br><br><br>
	</div>

	<?php if($post->profile->monthlyFee && $post->profile->minTip): ?>
	<div class="ml-2 p-2">
		<?php echo $__env->make('tips.tip-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
	<?php endif; ?>

<?php endif; ?>

</div>
<?php endif; ?>
<style>
.embed-container {
  --video--width: 1296;
  --video--height: 540;

  position: relative;
  padding-bottom: calc(var(--video--height) / var(--video--width) * 100%); /* 41.66666667% */
  overflow: hidden;
  max-width: 100%;
  background: black;
}

.embed-container iframe,
.embed-container object,
.embed-container embed {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
.postLike{
    color:#d88889;
}
.postLike:hover{
    color:#d88889;
}

.text-content {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    max-height: 4.9em; /* Adjust based on line height */
    position: relative;
}

.text-content.expanded {
    display: block;
    max-height: none;
}

.read-more {
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    text-align: left;
    padding: 0;
    margin-top: 10px;
}



</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get all elements with the class 'text-content'
    document.querySelectorAll('.text-content').forEach(function(textContent) {
        // Check if textContent contains img or video tags
        if (textContent.querySelector('img') || textContent.querySelector('video')) {
            return; // Do not show the button for content with images or videos
        }
        
        // Check if textContent exceeds 3 lines
        const lineHeight = parseFloat(getComputedStyle(textContent).lineHeight);
        const maxHeight = 3 * lineHeight;

        if (textContent.scrollHeight > maxHeight) {
            const postId = textContent.id.split('-').pop(); // Get the post ID
            const button = document.getElementById('read-more-' + postId);
            button.style.display = 'block';
        }
    });
});

function toggleText(postId) {
    const textContent = document.getElementById('text-content-' + postId);
    const button = document.getElementById('read-more-' + postId);
    
    if (textContent.classList.contains('expanded')) {
        textContent.classList.remove('expanded');
        button.textContent = 'Read More';
    } else {
        textContent.classList.add('expanded');
        button.textContent = 'Read Less';
    }
}

//video count JS code

</script>


<?php /**PATH C:\laragon\www\vipclub\resources\views/posts/single.blade.php ENDPATH**/ ?>