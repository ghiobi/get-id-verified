<?php if (Get_Id_Verified_Utils::user_is_verified($order->get_user_id())): ?>
  <div class="giv_badge giv_badge_success"><?= __('User is Verified') ?></div>
<?php else: ?>
  <a href="<?= get_edit_user_link($order->get_user_id()) ?>#giv_edit_profile" target="_new"><?= __('Verify User') ?></a>
<?php endif; ?>