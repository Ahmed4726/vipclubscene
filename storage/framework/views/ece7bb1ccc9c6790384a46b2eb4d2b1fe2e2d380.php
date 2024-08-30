<div wire:poll.3000ms>
<div>
    <a href="<?php echo e(route('messages.inbox')); ?>"><i class="fas fa-comments"></i>
         <?php echo app('translator')->get('navigation.messages'); ?>
        <span class="notifc">
            <?php echo e($count); ?>

        </span>
    </a>
</div>
<?php /**PATH C:\laragon\www\vipclub\resources\views/livewire/unread-messages-count.blade.php ENDPATH**/ ?>