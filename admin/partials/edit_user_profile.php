<h2 id="giv_edit_profile"><?= __('Government Id Verification') ?></h2>

<table class="form-table" role="presentation">
  <tbody>
    <tr>
      <th>
        <?= __('Verified') ?>
      </th>
      <td>
        <input type="checkbox" name="<?= GIV_USER_IS_VERIFIED_CHECKBOX ?>" value="1" <?= Get_Id_Verified_Utils::user_is_verified($profileuser->ID) ? 'checked' : '' ?>>
      </td>
    </tr>
    <tr>
      <th>
        <?= __('Image') ?>
      </th>
      <td>
        <?php if (!Get_Id_Verified_Utils::user_get_image_id($profileuser->ID)): ?>
          No Image
        <?php else: ?>
          <div style="max-width: 250px;">
            <?php
              $url = Get_Id_Verified_Utils::get_image_url_path(Get_Id_Verified_Utils::user_get_image_id($profileuser->ID));
              include __DIR__ . '/image_preview.php'; ?>
          </div>
        <?php endif; ?>
      </td>
    </tr>
  </tbody>
</table>