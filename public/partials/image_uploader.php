<?php do_action( 'giv_before_upload_widget'); ?>
<giv-uploader source="<?= $giv_image_upload_src ?? '' ?>" value="<?= $giv_image_upload_value ?? '' ?>" action="<?= site_url() ?>/wp-json/giv/v1/image"></giv-uploader>
