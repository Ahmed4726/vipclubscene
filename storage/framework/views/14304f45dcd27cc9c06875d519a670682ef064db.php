<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('navigation.editPost'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'content' ); ?>
	<div class="white-smoke-bg pt-4 pb-3">
		<div class="container add-padding">
			<div class="row">
				<div class="col-12 col-md-8">

					
					<div id="vue-update-post"></div>

				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush( 'extraJS' ); ?>
	<script src="<?php echo e(asset('resources/vueapp/dist/vuejs-bundle-v2.1.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make( 'welcome' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/k9n6iup22po9/public_html/VIPCLUBSCENE/resources/views/posts/edit-post.blade.php ENDPATH**/ ?>