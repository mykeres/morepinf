
let c = () => Array.from(document.getElementsByTagName("INPUT")).filter(cur => cur.type === 'checkbox' && cur.checked).length > 0;

document.getElementById("checkbox-tags").addEventListener("click", (e) =>{
	if(c()){
		document.getElementById("delete-tags").disabled = false;
		document.getElementById("add-tag").disabled = true;

	}else{
		document.getElementById("delete-tags").disabled = true;
		document.getElementById("add-tag").disabled = false;
	}	
})
