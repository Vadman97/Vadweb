$(".replyButton").click(function() {
  //alert($(this).data("index-number"));

  //var messageBody = '<div class="col-md-11">swag</div>';
  /*messageBody = '
	<form role="form" method="post" enctype="multipart/form-data" action="submitComment.php">
	  <div class="form-group"> 
	    <input type="text" id="comment" name="comment" class="form-control" placeholder="Comment here" style="width:100%">
 	  </div>
	  <input type="text" id="filename" name="filename" hidden="hidden" value="">
	  <button type="submit" class="btn btn-default">Submit</button>
	</form>
  ';*/
  //$(this).parent().after(messageBody);
  $(this).prop("disabled",true);
  $(this).parent().parent().find(".replyForm").removeAttr("hidden");
});