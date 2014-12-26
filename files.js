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
	//table.html("");
	var string = "";
	for (i = 0; i < files.length; i++) 
	{
		if (files[i].length == 6)
			var noPreview = true;
		else
			var noPreview = false;

	    string += '<tr>';
	    for (j = 0; j < files[i].length; j++)
	    {
	    	if (j == 2 && noPreview)
	    		string += "<td></td>";
	    	
	    	string += "<td>";
	    	string += files[i][j];
	    	string += "</td>";	
	    }
	    string += "</tr>";
	}
	table.append(string);
}

$(document).ready(function ()
{
	filesGet(1);
	page++;
	//getAllFiles();
});

$(window).scroll(function() {
    if($(window).scrollTop() == $(document).height() - $(window).height()) {
	loadMore();	    
    }
});
