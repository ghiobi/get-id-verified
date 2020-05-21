<div id="giv_upload_container">
  <input type="file" id="giv_upload_input" style="<?= empty($giv_image_upload_src) ? '' : 'display:none' ?>" accept="image/jpeg,image/png">
  <div id="giv_spinner" style="display: none"></div>

  <div id="giv_preview" style="<?= empty($giv_image_upload_src) ? 'display:none' : '' ?>">
    <img id="giv_upload_img" src="<?= $giv_image_upload_src ?? '' ?>">
    <button type="button"><?= __('Edit') ?></button>
  </div>

  <div><small><i><?= __('Only jpeg and png files are allowed with a maximum file size of 15mb.') ?></i></small></div>
  <input type="hidden" name="<?= GIV_IMAGE_UPLOAD_NAME ?>" value="<?= $giv_image_upload_value ?? '' ?>">
</div>
