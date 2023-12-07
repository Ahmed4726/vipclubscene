<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(url('js/tinymce/tinymce.min.js')); ?>"></script>
<script type="text/javascript">
tinymce.init({
    selector: '.textarea',
	plugins: 'image code link lists',
	images_upload_url: '/admin/cms/upload-image',
	toolbar: 'code | formatselect fontsizeselect | insertfile a11ycheck | numlist bullist | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image tinydrive',

});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('section_title'); ?>
	<strong>Pages Manager - Page Update</strong>
	<br/>
	<a href="<?php echo e(route('admin-cms')); ?>">Pages Overview</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>
	
	<form method="POST">
		<?php echo e(csrf_field()); ?>


		<dl>
		<dt>Page Title</dt>
		<dd><input type="text" name="page_title" class="form-control" value="<?php echo e($p->page_title); ?>"></dd>
		<br>
		<dt>Page Content</dt>
		<dd><textarea name="page_content" class="textarea form-control" rows="20"><?php echo $p->page_content; ?></textarea></dd>
		<dt>&nbsp;</dt>
		<dd><input type="submit" name="sb_page" class="btn btn-primary" value="Save"></dd>
		</dl>

	</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/k9n6iup22po9/public_html/VIPCLUBSCENE/resources/views/admin/update-page.blade.php ENDPATH**/ ?>