
function setComment(id)
{
	var xmlhttp;
			// if no id 
	if ( id.length==0 )
  	{
		return;
	}
							// get comment text
	comment_text = document.getElementById("comment").value;

				// if is text is empty
	if(comment_text.length==0)
	{
		return;
	}

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{		
			if(xmlhttp.responseText == 1)			
			{							// on response empty comment box
				comment_text = document.getElementById("comment").value = "";
				fetchComments(id); // fetch comments - refresh
			}
		}
	}

	xmlhttp.open("GET","includes/set_comment.php?picture_id="+id+"&comment_text="+comment_text,true);
	xmlhttp.send();
	
}
