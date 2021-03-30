<?php do_action( 'giv_before_upload_widget'); ?>
<giv-uploader source="<?= $giv_image_upload_src ?? '' ?>" value="<?= $giv_image_upload_value ?? '' ?>" action="<?= Get_Id_Verified_Utils::get_image_rest_url() ?>"></giv-uploader>
