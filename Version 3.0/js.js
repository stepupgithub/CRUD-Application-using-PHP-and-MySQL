function doValidate() 
{
console.log('Validating...');
  try 
	{

	pw = document.getElementById('inp2').value;
	em = document.getElementById('inp1').value;

	console.log("Validating pw="+pw);

	        if (pw == null || pw == "") 
	        	{
	        	alert("Both fields must be filled out");
	        	return false;
            	}
            if (em == null || em == "") 
            	{
            	alert("Both fields must be filled out");
	        	return false;
            	}
            if (em.indexOf("@")<0) 
            	{
            	alert("Invalid email address");
            	return false;
            	}


  	return true;

	} 
	catch(e) 
	{
    return true;
    }

return true;

}