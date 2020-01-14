document.getElementById('form-command').addEventListener('change',(e) => {
			if (e.target.value != '') {
				document.getElementById('form-button').classList.remove('hidden');
			} else {
				document.getElementById('form-button').classList.add('hidden');
			}

			if (e.target.value == 'etiqueta') {
				document.getElementById('form-tags').classList.remove('hidden');
			} else {
				document.getElementById('form-tags').classList.add('hidden');
			}
		});


let c = () => Array.from(document.getElementsByTagName("INPUT")).filter(cur => cur.type === 'checkbox' && cur.checked).length > 0;
document.getElementById("form-button").addEventListener("click", (e) => {
 if(!c()) { 
 	alert('No hay ninguna imagen escogida');
	e.preventDefault();
} 
});
