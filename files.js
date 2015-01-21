var filesList = "";
var page = 1;

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
		if (files[i].length == 6)
		{
			//var noPreview = true; <<proper for thumbnails
			var noPreview = true;
		}
		else
		{
			//var noPreview = false; <<proper for thumbnails
			var noPreview = false;
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
