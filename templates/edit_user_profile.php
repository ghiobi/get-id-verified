<h2 id="giv_edit_profile"><?= __('Government Id Verification') ?></h2>

<table class="form-table" role="presentation">
  <tbody>
    <tr>
      <th>
        <?= __('Verified') ?>
      </th>
      <td>
        <input type="checkbox" name="<?= GIV_USER_IS_VERIFIED_CHECKBOX ?>" value="1" <?= giv_user_is_verified($profileuser->ID) ? 'checked' : '' ?>>
      </td>
    </tr>
    <tr>
      <th>
        <?= __('Image') ?>
      </th>
      <td>
        <?php if (!giv_user_get_image_id($profileuser->ID)): ?>
          No Image
        <?php else: ?>
          <img style="max-width: 250px;" src="<?= giv_get_image_url_path(giv_user_get_image_id($profileuser->ID)) ?>">
        <?php endif; ?>
      </td>
    </tr>
  </tbody>
</table>