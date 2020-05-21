<script>
	export let source = '';
	export let value = '';
	export let action = '';
	export let uploading = false;

	const onEdit = () => {
		source = '';
		value = '';
	};

	const getSubmitBtn = (target) => {
		const form = target.closest('form');
		return form ? form.querySelector('button[type="submit"]') : null;
	};

	const onImageSelect = ({ target }) => {
		const xhr = new XMLHttpRequest();

    xhr.open("POST", action, true);    

		const file = target.files[0];
		const submit = getSubmitBtn(target);
		
    if (file) {
			if (file.size > 15000000 ) { // 15MB 
				return alert('Please upload a file less than 15MB.');
			}
			const ext = file.name.split('.').pop();
			if (!ext.match(/(jpg|jpeg|png)/)) {
				return alert('Only jpg or png images are allowed.');
			}

			const form = new FormData();
			form.append('giv_upload_image', file);
			xhr.send(form);

			uploading = true;
			submit && submit.setAttribute('disabled', 'disabled');
		} else {
			alert('Please select a file.');
		}
		
    xhr.onreadystatechange = () => {
			if(xhr.readyState == 4 && xhr.status == 200) {
				value = JSON.parse(xhr.response);
				source = `${action}/${value}?tmp=true`;
			}

			submit && submit.removeAttribute('disabled');
			uploading = false;
		}
	};
</script>

<div class="giv_container">
	{#if !source}
		<div class="giv_uploader">
			{#if uploading}
				<div class="giv_spinner"></div>
			{/if}
			<input type="file" accept="image/jpeg,image/png" on:change={onImageSelect}>
		</div>
	{:else}
		<div class="giv_preview">
			<img class="giv_preview-image" src="{source}" alt="Government Id">
			<button class="giv_preview-edit" type="button" on:click={onEdit}>Edit</button>
		</div>
	{/if}

  <input type="hidden" name="giv_upload_image_name" value="{value}">
</div>


<style>
	.giv_container {
		position: relative;
		margin-bottom: 1rem;
	}

	@media screen and (min-width: 768px) {
		.giv_container { 
			max-width: 26rem;
		}
	}

	.giv_uploader {
		display: relative;
	}

	.giv_preview {
		display: inline-block;
		position: relative;
	}

	.giv_preview-edit {
		position: absolute;
		top: 5px;
		right: 5px;
		padding: .2rem .6rem;
		font-size: .8rem;
		color: #fff;
		background-color: #000;
		border: 0;
		line-height: initial;
	}

	.giv_preview-image {
		max-width: 100%;
	}

	.giv_spinner::before {
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		display: inline-block;
		font-style: normal;
		font-variant: normal;
		font-weight: normal;
		line-height: 1;
		font-family: 'Font Awesome 5 Free';
		font-weight: 900;
		line-height: inherit;
		vertical-align: baseline;
		content: "\f110";
		-webkit-animation: fa-spin 0.75s linear infinite;
		animation: fa-spin 0.75s linear infinite;
    font-size: 1.5rem;
    position: absolute;
    right: 0;
    top: -6px;
	}
</style>