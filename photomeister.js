//function to create another upload field
var i = 2;
function loadUpload() {
	var form = document.getElementById("files");
	
	var liEle = document.createElement("li");
	
	var inputEle = document.createElement("input");
	inputEle.setAttribute("type","file");
	inputEle.setAttribute("name","image"+i);
	
	liEle.appendChild(inputEle);
	
	form.appendChild(liEle);
	
	i++;
}

//save the album order
function saveAlbumOrder() {
	var container = document.getElementById("imageList");
	
	var imageArray = [];
	
	var images = container.childNodes;
	
	var j = 0;
	for(var i = 0; i < images.length; i++) {
		if(images[i].id != undefined) {
			//alert(images[i].id);
			imageArray[j] = images[i].id;
			j++;
		}
	}
	
	$.post("test.php", { 'images' : [imageArray] });
}