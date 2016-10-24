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
					loading = false;
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

var loading = false;

$(window).scroll(function() {
    var body = document.body,
    html = document.documentElement;
 
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
                           html.clientHeight, html.scrollHeight, html.offsetHeight );

    console.log($(window).scrollTop());
    console.log(height);

    if($(window).scrollTop() + 720 + (height/3) >= height && !loading) {
	loadMore();
	loading = true;	    
    }
});


// Created by STRd6
// MIT License
// jquery.paste_image_reader.js
(function($) {
  var defaults;
  $.event.fix = (function(originalFix) {
    return function(event) {
      event = originalFix.apply(this, arguments);
      if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
        event.clipboardData = event.originalEvent.clipboardData;
      }
      return event;
    };
  })($.event.fix);
  defaults = {
    callback: $.noop,
    matchType: /image.*/
  };
  return $.fn.pasteImageReader = function(options) {
    if (typeof options === "function") {
      options = {
        callback: options
      };
    }
    options = $.extend({}, defaults, options);
    return this.each(function() {
      var $this, element;
      element = this;
      $this = $(this);
      return $this.bind('paste', function(event) {
        var clipboardData, found;
        found = false;
        clipboardData = event.clipboardData;
        return Array.prototype.forEach.call(clipboardData.types, function(type, i) {
          var file, reader;
          if (found) {
            return;
          }
          if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
            file = clipboardData.items[i].getAsFile();
            reader = new FileReader();
            reader.onload = function(evt) {
              return options.callback.call(element, {
                dataURL: evt.target.result,
                event: evt,
                file: file,
                name: file.name
              });
            };
            reader.readAsDataURL(file);
            return found = true;
          }
        });
      });
    });
  };
})(jQuery);
