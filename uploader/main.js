import Uploader from './Uploader.svelte';


window.addEventListener('DOMContentLoaded', () => {
	let target = document.getElementsByTagName('giv-uploader');

	if (target && target.length) {
		target = target[0];

		new Uploader({
			target,
			props: {
				source: target.getAttribute('source'),
				value: target.getAttribute('value'),
				action: target.getAttribute('action'),
			}
		});
	}
});
