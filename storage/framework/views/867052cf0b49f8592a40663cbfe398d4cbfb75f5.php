<div class="col-12 col-md-4 d-none d-sm-none d-md-block d-lg-block" style="margin-top:-30px">
	<?php if( isset($feed) && $feed->count() ): ?>
		<input type="number" class="lastId d-none" value="<?php echo e($feed->last()->id); ?>">
	<?php endif; ?>

	<?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('creators-sidebar')->dom;
} elseif ($_instance->childHasBeenRendered('CM2bB6O')) {
    $componentId = $_instance->getRenderedChildComponentId('CM2bB6O');
    $componentTag = $_instance->getRenderedChildComponentTagName('CM2bB6O');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('CM2bB6O');
} else {
    $response = \Livewire\Livewire::mount('creators-sidebar');
    $dom = $response->dom;
    $_instance->logRenderedChild('CM2bB6O', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>

	<br>
</div><?php /**PATH /home/k9n6iup22po9/public_html/VIPCLUBSCENE/resources/views/posts/sidebar-desktop.blade.php ENDPATH**/ ?>