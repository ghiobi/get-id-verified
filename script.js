jQuery(() => {
  const $ = jQuery;
  const url = '/wp-json/giv/v1/image';
  const upload = $('#giv_upload_input');
  const image = $('#giv_upload_img');
  const preview = $('#giv_preview');
  const preview_button = $('#giv_preview button');
  const spinner = $('#giv_spinner');
  const hidden = $('[name="giv_upload_image_name"]');

  upload.change(() => {
    const el = upload[0];

    if (!el.files.length) {
      return;
    }

    const data = new FormData();
    data.append('giv_upload_image', el.files[0]);

    const button = upload.closest('form').find('button[type="submit"]');
    button.attr('disabled', 'disabled');
    spinner.show();

    $.ajax({
      type: "POST",
      url,
      enctype: 'multipart/form-data',
      data,
      processData: false,
      contentType: false
    }).done((data) => {
      preview.show();
      upload.hide();
      upload.val('');
      hidden.val(data);
      image.attr('src', url + `/${data}?tmp=true`);

      button.removeAttr('disabled');
      spinner.hide();
    });
  });

  preview_button.click(() => {
    preview.hide();
    upload.show();
    hidden.val('');
  });
});