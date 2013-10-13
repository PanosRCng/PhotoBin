function check_imageinfo_Fields(form)
{
	title = description = true;

	title = checkIfempty('title');	
	description = checkIfempty('description');

	if(title && description)
	{
		return true;
	}
	else
	{
		return false;
	}			
}

function checkIfempty(field)
{		
	if( document.getElementById(field).value == "" )
	{
		document.getElementById(field).value = "You forgot to enter your " + field;
		return false;
	}

	return true;
}


function checkFields(form)
{
	firstname =lastname = username = email = password = true;

	firstname = checkFirstname();	
	lastname = checkLastname();
	username = checkUsername();
	email = checkMail();
//	password = checkPassword();


	if(firstname && lastname && username && email && password)
	{
		return true;
	}
	else
	{
		return false;
	}			
}

function check_editprofile_Fields(form)
{
	firstname =lastname = email = true;

	firstname = checkFirstname();	
	lastname = checkLastname();
	email = checkMail();

	if(firstname && lastname && username)
	{
		return true;
	}
	else
	{
		return false;
	}			
}

function checkFirstname()
{		
	if( document.getElementById("firstname").value == "" )
	{
		document.getElementById("firstname").value = "You forgot to enter your firstname";
		return false;
	}

	return true;
}

function checkLastname()
{		
	if( document.getElementById("lastname").value == "" )
	{
		document.getElementById("lastname").value = "You forgot to enter your lastname";
		return false;
	}

	return true;
}

function checkMail()
{		
	email = document.getElementById("email").value;

	if( email == "" )
	{
		document.getElementById("email").value = "You forgot to enter your email";
		return false;
	}

	n = email.indexOf("@");

	if(n == -1)
	{
		document.getElementById("email").value = "No valid email";
		return false;
	}

	return true;
}

function checkUsername()
{
	completed = true;
		
	if( document.getElementById("username").value == "" )
	{
		document.getElementById("username").value = "You forgot to enter your username";
		completed = false;
	}
			
	if(!completed)
		return false;
	else
		return true;
}

function checkPassword()
{
	pass = document.getElementById("password").value;
	pass1 = document.getElementById("confirm").value
	errors = "";
			
	var special_chars = new Array();
			special_chars[0] = "!";
			special_chars[1] = "\"";
			special_chars[2] = "#";
			special_chars[3] = "$";
			special_chars[4] = "%";
			special_chars[5] = "&";
			special_chars[6] = "\\";
			special_chars[7] = "(";
			special_chars[8] = ")";
			special_chars[9] = "*";
			special_chars[10] = "+";
			special_chars[11] = ",";
			special_chars[12] = ".";
			special_chars[13] = "/";
			special_chars[14] = ":";
			special_chars[15] = ";";
			special_chars[16] = "<";
			special_chars[17] = "=";
			special_chars[18] = ">";
			special_chars[19] = "?";
			special_chars[20] = "@";
			special_chars[21] = "[";
			special_chars[22] = "\\";
			special_chars[23] = "]";
			special_chars[24] = "^";
			special_chars[25] = "_";
			special_chars[26] = "`";
			special_chars[27] = "{";
			special_chars[28] = "|";
			special_chars[29] = "}";
			special_chars[30] = "~";
			special_chars[31] = "-";
			
	var numbers = new Array();
			numbers[0] = "0";
			numbers[1] = "1";
			numbers[2] = "2";			
			numbers[3] = "3";
			numbers[4] = "4";
			numbers[5] = "5";
			numbers[6] = "6";
			numbers[7] = "7";
			numbers[8] = "8";
			numbers[9] = "9";
			
			
			
			//check if passwords match			
			if(pass != pass1)
			{
				return false;
			}
			
			//check if password has the desired size
			pl = false;
			l = pass.length;
			
			if(l < 6)
			{
				errors = "Your password must be at least 6 characters long!\n";
				pl = false;
			}
			else
				pl = true;
				
			if(!pl)
			{
				alert(errors);
				return false;
			}
					
			
			//alert("test");//debug alert
			
			//check if password contains special characters
			sp =false;
			for(i = 0; i < 32; i++)
			{
				n = pass.indexOf(special_chars[i]);
				if(n != -1)
				{
					sp = true;
					//alert("Your password is acceptable!");
					break;
				}else{
					errors = "Your password must contain at least one special character!\n";
					sp=false;
				}
			}
			if(!sp)
			{
				alert(errors);
				return false;
			}
			
			//check if password contains numbers
			np = false;
			for(i = 0; i < 10; i++)
			{
				n = pass.indexOf(numbers[i]);
				if(n != -1)
				{
					np = true;
					break;
				}else
				{
					errors = "Your password must contain at least one number!\n";
					np = false;
				}
			}
			if(!np)
			{
				alert(errors);
				return false;
			}
			
			
			//check if password contains uppercase letters
			up = false;
			
			spchars="";
			for(i = 0; i < 32; i++)
				spchars= spchars + special_chars[i];
				
			//alert(spchars);//debug alert
				
			nums="";
			for(i = 0; i < 10; i++)
				nums = nums + numbers[i];
			
			//alert(nums);//debug alert
			
			
			c="";
			spchar = false;
			//remove special characters from password
			for(i = 0; i < pass.length; i++)
			{
				//c = c + pass.charAt(i);
				
				for(j = 0; j < 32; j++)
				{
					if(pass.charAt(i) == spchars.charAt(j))
					{
						spchar=true;
						break;
					}
					else
						spchar=false;
				}
				if(!spchar)
				{
					c = c + pass.charAt(i);
				}
			}
			
			//alert(c);//debug alert
			
			d="";
			nchar = false;
			//remove numbers from password
			for(i = 0; i < c.length; i++)
			{
				for(j = 0; j < 10; j++)
				{
					if(c.charAt(i) == nums.charAt(j))
					{
						nchar = true;
						break;
					}
					else
						nchar = false;
				}
				if(!nchar)
				{
					d = d + c.charAt(i);
				}
			
			}
			
			//alert(d);//debug alert
			
			
			//check if password has uppercase characters
			for(i = 0; i < d.length; i++)
			{
				a = d;
				code = a.charCodeAt(i);
				
				b = d.toUpperCase();
				code1 = b.charCodeAt(i);
				
				up=false;
				
				if(code == code1)
				{
					up=true;
					break;
				}
				else
				{
					up=false;
					errors = "Your password must have uppercase characters!";
				}
			}
			
			if(!up)
			{
				alert(errors);
				return false;
			}
			
			//check if password has lowercase characters
			for(i = 0; i < d.length; i++)
			{
				a = d;
				code = a.charCodeAt(i);
				
				b = d.toLowerCase();
				code1 = b.charCodeAt(i);
				
				down = false;
				
				if(code == code1)
				{
					down = true;
					break;
				}
				else
				{
					down=false;
					errors = "Your password must have lowercase characters!";
				}
			}
			
			if(!down)
			{
				alert(errors);
				return false;
			}
			
	return true;
}

function clearField(field_id)
{
	document.getElementById(field_id).value = "";
}

