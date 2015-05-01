var filesList = "";
var page = 1;

function loading()
{
	$("#loading").removeAttr("hidden");
}

function filesGet(page)
{
	var ajax = $.ajax(
		{
			type: "GET",
			url: "getFiles.php",
			data: "page=" + page,
			async: true,
			success: 
				function(response) 
				{
					$("#fileTable").html("");
					filesList = response;
					displayFiles(response);
				}
		});
	return filesList;
}

function getAllFiles()
{
	$.ajax(
		{
			type: "GET",
			url: "getFiles.php",
			data: "page=" + page,
			async: true,
			success: 
				function(response) 
				{
					//console.log(page);
					if (response == "-1")
					{
						//console.log("Done!");
						return;
					}

					displayFiles(response);
					page++;
					getAllFiles();
					//setTimeout(getAllFiles(), 5000);
				}
		});
}

function loadMore()
{
        $.ajax(
                {
                        type: "GET",
                        url: "getFiles.php",
                        data: "page=" + page,
                        async: true,
                        success:
                                function(response)
                                {
                                        if (response == "-1")
                                        {
                                                //console.log("Done!");
                                                return;
                                        }
                                        displayFiles(response);
                                }
                });
	page++;
	console.log("Loading another page: " + page);
}

function displayFiles(data)
{
	var table = $("#fileTable");
	files = JSON.parse(data);
	console.log(files);
	//table.html("");
	var string = "";
	for (i = 0; i < files.length; i++) 
	{
		var noPreview = true;
		for (k = 0; k < files[i].length; k++)
		{
			if (files[i][k].indexOf("img") > -1)
			{
				noPreview = false;
			}
			if (files[i][k].indexOf("Sp00ky") > -1)
			{
				noPreview = false;
			}
		}

		if ($.inArray("unlisted", files[i]) != -1)
		{
			string += '<tr style="border-style: solid; border-color: #0000ff;">';
		}
		else
		{
	   		string += '<tr>';
		}
	    for (j = 0; j < files[i].length; j++)
	    {
	    	if (files[i][j] == "unlisted")
	    		continue;
	    	if (j == 2 && noPreview)
	    	{
	    		string += "<td></td>";
	    		//continue;
	    	}
	    	
	    	string += "<td>";
	    	string += files[i][j];
	    	string += "</td>";	
	    }
	    string += "</tr>";
	}
	table.append(string);
}

function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
            return decodeURIComponent(pair[1]);
        }
    }
    console.log('Query variable %s not found', variable);
}

$(document).ready(function ()
{
	//filesGet(0);
	console.log(getQueryVariable("page"));
	if (getQueryVariable("page") != null)
	{
		filesGet(getQueryVariable("page"));
		page = getQueryVariable("page") + 1;
	}
	else
	{
		filesGet(1);
		page++;
	}
	//getAllFiles();
});

$(window).scroll(function() {
	console.log($(document).height() - $(window).height());
	console.log($(window).scrollTop());

    if($(window).scrollTop() + 500 >= $(document).height() - $(window).height()) {
	loadMore();	    
    }
});


//Define paster object with contructor
var Paster = function(config) {

 for(var key in config){
  this[key]=config[key];
 }
 
 this.init();
};

Paster.prototype.pasteEl=null;

Paster.prototype.init=function() {

 var paster = this;

 if (window.Clipboard) {
  //IE11, Chrome, Safari
  this.pasteEl.onpaste=function(e){
   paster.handlePaste(paster, e);
  };
 } else
 {
  //On Firefox use the contenteditable div hack
  this.canvas = document.createElement('canvas');
  this.pasteCatcher = document.createElement("div");
  this.pasteCatcher.setAttribute("id", "paste_ff");
  this.pasteCatcher.setAttribute("contenteditable", "");
  this.pasteCatcher.style.cssText = 'opacity:0;position:fixed;top:0px;left:0px;';
  this.pasteCatcher.style.marginLeft = "-20px";
  document.body.appendChild(this.pasteCatcher);


  this.pasteEl.onblur=function(e) {
   paster.pasteCatcher.focus();
  };

  this.pasteCatcher.onpaste=function(e) {

   paster.findImageEl(paster);
  };
 }
};

 
Paster.prototype.dataURItoBlob=function(dataURI, callback) {
// convert base64 to raw binary data held in a string
// doesn't handle URLEncoded DataURIs - see SO answer #6850276 for code that does this

 console.log(dataURI);

 var byteString = atob(dataURI.split(',')[1]);
// separate out the mime component
 var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]

// write the bytes of the string to an ArrayBuffer
 var ab = new ArrayBuffer(byteString.length);
 var ia = new Uint8Array(ab);
 for (var i = 0; i < byteString.length; i++) {
  ia[i] = byteString.charCodeAt(i);
 }

// write the ArrayBuffer to a blob, and you're done

 return new Blob([ia], {type: mimeString});
};

Paster.prototype.findImageEl = function(paster) {

 if (paster.pasteCatcher.children.length > 0) {

  var dataURI = paster.pasteCatcher.firstElementChild.src;
  if (dataURI) {
   if (dataURI.indexOf('base64') === -1) {
    alert("Sorry, with Firefox you can only paste local screenshots and files. Use Chrome or IE11 if you need paster feature.");
    return;
   }

   var file = paster.dataURItoBlob(dataURI);
   paster.uploadFile(paster, file);
  }

  paster.pasteCatcher.innerHTML = '';

 } else
 {
  setTimeout(function() {
   paster.findImageEl(paster);
  }, 100);
 }
};

Paster.prototype.processing = false; //some wierd chrome bug makes the paste event fire twice when using javascript prompt for the filename

Paster.prototype.handlePaste = function(paster, e) {

 //don't do this twice
 if (paster.processing) {
  return;
 }

 //loop through all clipBoardData items and upload it if it's a file.
 for (var i = 0; i < e.clipboardData.items.length; i++) {
  var item = e.clipboardData.items[i];
  if (item.kind === "file") {

   paster.processing = true;
   e.preventDefault();
   paster.uploadFile(paster, item.getAsFile());
  }
 }
};


Paster.prototype.uploadFile = function(paster, file) {

 var xhr = new XMLHttpRequest();

 //progress logging
 xhr.upload.onprogress = function(e) {
  var percentComplete = (e.loaded / e.total) * 100;

  console.log(percentComplete);
 };

 //called when finished
 xhr.onload = function() {
  if (xhr.status === 200) {
   alert("Sucess! Upload completed. PHP response will be put in the textarea.");
  } else {
   alert("Error! Upload failed");
  }

  paster.processing = false;
 };

 //error handling
 xhr.onerror = function() {
  alert("Error! Upload failed. Can not connect to server.");
 };

 //trigger a callback when it's successful
 xhr.onreadystatechange = function()
 {
  if (xhr.readyState === 4 && xhr.status === 200)
  {
   if (paster.callback) {
    paster.callback.call(paster.scope || paster, paster, xhr);
   }
  }
 };


 //prompt for the filename
 var filename = prompt("Please enter the file name", "Pasted image");

 if (filename) {

  //upload the file
  xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", {
   filename: filename,
   filetype: file.type
  });
  
  //send it as multipart/form-data
  var formData = new FormData();
  formData.append("pastedFile", file);
  xhr.send(formData);
 }
};