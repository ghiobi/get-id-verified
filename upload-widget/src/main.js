import App from './App.svelte';


window.addEventListener('DOMContentLoaded', () => {
	let target = document.getElementsByTagName('giv-uploader');

	if (target && target.length) {
		target = target[0];

		new App({
			target,
			props: {
				source: target.getAttribute('source'),
				value: target.getAttribute('value'),
				action: target.getAttribute('action'),
			}
		});
	}
});
