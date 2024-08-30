<link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
<?php if( $post->media_type == 'Image' ): ?>

<?php if($post->postmedia->count()): ?>
        
        <div class="row">

            <div class="col-6 col-sm-6 col-md-6 mt-2 mb-2">

                <?php if( $post->disk == 'backblaze' ): ?>
                    <a href="javascript:void(0);" data-toggle="lightbox" data-remote="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>" data-gallery="post-<?php echo e($post->id); ?>">
                        <img src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>" alt="" class="img-fluid"/>
                    </a>
                <?php else: ?>
                    <a href="javascript:void(0);" data-toggle="lightbox" data-remote="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>" data-gallery="post-<?php echo e($post->id); ?>">
                        <img src="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>" alt="" class="img-fluid"/>
                    </a>
                <?php endif; ?>

            </div>
            
            <?php $__currentLoopData = $post->postmedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraMedia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="col-6 col-sm-6 col-md-6 mt-2 mb-2">
                    <?php if( $post->disk == 'backblaze' ): ?>
                        <a href="javascript:void(0);" data-toggle="lightbox" data-remote="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $extraMedia->media_content); ?>" data-gallery="post-<?php echo e($post->id); ?>">
                            <img src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $extraMedia->media_content); ?>" alt="" class="img-fluid"/>
                        </a>
                    <?php else: ?>
                        <a href="javascript:void(0);" data-toggle="lightbox" data-remote="<?php echo e(\Storage::disk($extraMedia->disk)->url($extraMedia->media_content)); ?>" data-gallery="post-<?php echo e($post->id); ?>">
                            <img src="<?php echo e(\Storage::disk($extraMedia->disk)->url($extraMedia->media_content)); ?>" alt="" class="img-fluid"/>
                        </a>
                    <?php endif; ?>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    <?php else: ?>

        <?php if( $post->disk == 'backblaze' ): ?>
            <a href="javascript:void(0);" data-toggle="lightbox" data-remote="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>">
                <img src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>" alt="" class="img-fluid"/>
            </a>
        <?php else: ?>
            <a href="javascript:void(0);" data-toggle="lightbox" data-remote="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>">
                <img src="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>" alt="" class="img-fluid"/>
            </a>
        <?php endif; ?>

    <?php endif; ?>

<?php elseif( $post->media_type == 'Video' ): ?>

<div class="embed-responsive embed-responsive-16by9">
<video id="video-<?php echo e($post->id); ?>" controls <?php if(opt('enableMediaDownload', 'No') == 'No'): ?> controlsList="nodownload" <?php endif; ?> preload="metadata" disablePictureInPicture>
    <?php if( $post->disk == 'backblaze' ): ?>
        <source src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>#t=0.5" type="video/mp4" />
    <?php elseif($post->disk == 's3'): ?>
        <source src="<?php echo e(\Storage::disk($post->disk)->url($post->video_url)); ?>#t=0.5" type="video/mp4" />
        <?php else: ?>
        <source src="<?php echo e('https://vipclubscene.com' . '/public/uploads/' . $post->video_url); ?>#t=0.5" type="video/mp4" />
    <?php endif; ?>
    <?php echo app('translator')->get('post.videoTag'); ?>
</video>
</div>

<?php elseif( $post->media_type == 'live' ): ?>

    <video id="video-<?php echo e($post->id); ?>" controls width="657" height="300" class="video-js vjs-default-skin" <?php if(opt('enableMediaDownload', 'No') == 'No'): ?> controlsList="nodownload" <?php endif; ?> preload="metadata" disablePictureInPicture>
        <source type="application/x-mpegURL" src="<?php echo e('https://dlhgx48j8zr2n.cloudfront.net/' . $post->live_stream_url); ?>">
    </video>



<?php elseif( $post->media_type == 'Audio' ): ?>

<div class="p-2">
<audio class="w-100 mb-4" controls <?php if(opt('enableMediaDownload', 'No') == 'No'): ?> controlsList="nodownload" <?php endif; ?>>
    <?php if( $post->disk == 'backblaze' ): ?>
        <source src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>" type="audio/mp3">
    <?php else: ?>
        <source src="<?php echo e(\Storage::disk($post->disk)->url($post->audio_url)); ?>" type="audio/mp3">
    <?php endif; ?>
    <?php echo app('translator')->get('post.audioTag'); ?>
</audio>
</div>

<?php elseif( $post->media_type == 'ZIP' ): ?>

<h5>
    <a href="<?php echo e(route('downloadZip', ['post' => $post])); ?>" target="_blank" class="ml-4 mb-3">
        <i class="fas fa-file-archive"></i> <?php echo app('translator')->get('v16.zipDownload'); ?>
    </a>
</h5><br>

<?php endif; ?>

<?php $__env->startPush('extraCSS'); ?>
<link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
<style>
    .ekko-lightbox-nav-overlay a {
        opacity:1;
        color:black;
    }
.embed-responsive {
    position: relative;
    display: block;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
}

.embed-responsive video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
    object-fit: cover; /* Ensure the video covers the container */
}

</style>
<?php $__env->stopPush(); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://vjs.zencdn.net/7.2.3/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>
<script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>

<script>
$(document).ready(function() {
    <?php if($post->media_type == 'Video' || $post->media_type == 'live'): ?>
        var video = document.getElementById('video-<?php echo e($post->id); ?>');
        video.addEventListener('play', function() {
            $.ajax({
                url: "<?php echo e(route('posts.incrementView', ['post' => $post->id])); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                },
                success: function(response) {
                    if (response.success) {
                        $('#views-count-<?php echo e($post->id); ?>').text(response.views);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    <?php endif; ?>
});

var player = videojs('video-<?php echo e($post->id); ?>');
    player.ready(function() {
        player.play();
    });
</script>
<?php /**PATH C:\laragon\www\vipclub\resources\views/posts/post-media.blade.php ENDPATH**/ ?>