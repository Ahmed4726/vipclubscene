<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('navigation.my-subscriptions'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'account_section' ); ?>

<div>


<div class="shadow-sm card add-padding">
	<h2 class="mt-3 ml-2 mb-4">
		<i class="fas fa-user-edit mr-1"></i> <?php echo app('translator')->get('navigation.my-subscriptions'); ?>
	</h2>

	<?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('user-subscriptions-list')->dom;
} elseif ($_instance->childHasBeenRendered('EbuyHKb')) {
    $componentId = $_instance->getRenderedChildComponentId('EbuyHKb');
    $componentTag = $_instance->getRenderedChildComponentTagName('EbuyHKb');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('EbuyHKb');
} else {
    $response = \Livewire\Livewire::mount('user-subscriptions-list');
    $dom = $response->dom;
    $_instance->logRenderedChild('EbuyHKb', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
	
</div>

<br/><br/>
</div><!-- /.white-smoke-bg -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make( 'account' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/k9n6iup22po9/public_html/VIPCLUBSCENE/resources/views/profile/my-subscriptions.blade.php ENDPATH**/ ?>