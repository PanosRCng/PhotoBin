
function deleteComment(picture_id, comment_id)
{
	var xmlhttp;
							// if no ids 
	if( (picture_id.length==0) || (comment_id.length==0) )
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
			{						
				fetchComments(picture_id); // fetch comments - refresh
			}
		}
	}

	xmlhttp.open("GET","includes/delete_comment.php?comment_id="+comment_id,true);
	xmlhttp.send();
	
}
