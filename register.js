// JavaScript Document
function runReg()
{
	$('#ajax-panel').empty();
	$('#ajax-panel').html('<div class="loading"><b>Loading...     </b><img src="/images/loading.gif" alt="Loading..." /></div>');
	
	var _salt = "swagger";
	var saltGet = $.ajax(
	{
		type: "GET",
		url: "/getSalt.php",
		data: "&d=y",
		async:false,
		success: 
			function(data) 
			{
				_salt = data;
			}
	});
	
	console.log("Salt: " + ">" + _salt + "<");
	
	var formData = $('#regForm').serialize();
	var password1 = formData.substring(formData.indexOf("password=", 0) + 9, formData.indexOf("password2=", 0) - 1);
	var password2 = formData.substring(formData.indexOf("password2=", 0) + 10, formData.length);
	console.log("P1: " + password1);
	console.log("P2: " + password2);
	
	
	var key = CryptoJS.PBKDF2("swag Swag", _salt, { keySize: 256/32, iterations: 500 });
	var iv  = CryptoJS.enc.Hex.parse('101112131415161718191a1b1c1d1e1f'); // just chosen for an example, usually random as well
	
	var encP1 = CryptoJS.AES.encrypt(password1, key, { iv: iv });
	var encP2 = CryptoJS.AES.encrypt(password2, key, { iv: iv });
	
	var P1data_base64 = encP1.ciphertext.toString(CryptoJS.enc.Base64); 
	var P1iv_base64   = encP1.iv.toString(CryptoJS.enc.Base64);       
	var P1key_base64  = encP1.key.toString(CryptoJS.enc.Base64);
	
	var P2data_base64 = encP2.ciphertext.toString(CryptoJS.enc.Base64); 
	var P2iv_base64   = encP2.iv.toString(CryptoJS.enc.Base64);       
	var P2key_base64  = encP2.key.toString(CryptoJS.enc.Base64);
	
	console.log("P1 Data: " + ">" + P1data_base64 + "<");
	console.log("P1 IV: " + ">" + P1iv_base64 + "<");
	console.log("P1 Key: " + ">" + P1key_base64 + "<");
	
	console.log("P2 Data: " + ">" + P2data_base64 + "<");
	console.log("P2 IV: " + ">" + P2iv_base64 + "<");
	console.log("P2 Key: " + ">" + P2key_base64 + "<");
	
	
	/*var encP1 = CryptoJS.AES.encrypt(password1, _salt);
	var encP2 = CryptoJS.AES.encrypt(password2, _salt);
	var encP1Str = encP1.toString();
	var encP2Str = encP2.toString();
	
	console.log("Enc P1: " + ">" + encP1Str + "<");
	console.log("Enc P2: " + ">" + encP2Str + "<");*/
	
	/*var decryptedP1 = CryptoJS.AES.decrypt(encP1, _salt);
	var decryptedP2 = CryptoJS.AES.decrypt(encP2, _salt);
	var decStringP1 = decryptedP1.toString(CryptoJS.enc.Utf8);
	var decStringP2 = decryptedP2.toString(CryptoJS.enc.Utf8);
	console.log("Dec P1: " + decStringP1);
	console.log("Dec P2: " + decStringP2);*/
	
	/*var encrypted = CryptoJS.AES.encrypt("Message", _salt);
	var decrypted = CryptoJS.AES.decrypt(encrypted, _salt);
	var encString = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
	var decString = decrypted.toString(CryptoJS.enc.Utf8);
	
	console.log("Enc string  " + encString);
	console.log("Dec string  " + decString);
	console.log("Form string  " + formData);*/
	var newFormData = $('#regForm').serializeArray();
	/*newFormData[3].value = P1data_base64;
	newFormData[4].value = P2data_base64;*/
	newFormData.push({
		name: "p1Enc",
		value: P1data_base64
	});
	newFormData.push({
		name: "p2Enc",
		value: P2data_base64
	});
	newFormData.push({
		name: "iv",
		value: P1iv_base64
	});
	newFormData.push({
		name: "k",
		value: P1key_base64
	});
	
	newFormData = jQuery.param(newFormData);
	
	console.log("Form string  " + newFormData);
	
	var request = $.ajax(
	{
		type: "POST",
		url: "/register.php",
		data: newFormData, //transmit encrypted passwords here
		beforeSend:
			function()
			{
				$('#ajax-panel').html('<div class="loading"><b>Loading...     </b><img src="/images/loading.gif" alt="Loading..." /></div>');
			},
		success: 
			function(data) 
			{
				var datToDisplay = "<b>ERROR: ";
				var noWriteClose = false;
				var success = false;
				$('#ajax-panel').empty();
				switch(data)
				{
					case "E1":
						datToDisplay += "Invalid Username!";
						break;
					case "E2":
						datToDisplay += "Invalid Email!";
						break;
					case "E3":
						datToDisplay += "Invalid Year of Birth!";
						break;
					case "E4":
						datToDisplay += "User already exists with given username or email!";
						break;
					case "E5":
						datToDisplay += "Empty fields!";
						break;
					case "E6":
						datToDisplay += "Username cannot contain a space!";
						break;
					case "E7":
						datToDisplay += "Username too long! Limit 20 characters.";
						break;
					case "E8":
						datToDisplay += "Email too long! Limit 50 characters.";
						break;
					case "E9":
						datToDisplay += "Password too long! Limit 50 characters.";
						break;
					case "E10":
						datToDisplay += "Username too short! Must be greater than 3 characters long.";
						break;
					case "E11":
						datToDisplay += "Password too short! Must be greater than 7 characters long.";
						break;
					case "E12":
						datToDisplay += "Passwords do not match!";
						break;
					case "F":
						datToDisplay = "<b>Failed to write user.";
						break;
					case "S":
						datToDisplay = "<b>Success! You will be redirected to the home page.</b>";
						$('head').append('<meta http-equiv="refresh" content="3; url=http://vadweb.us/" />');
						noWriteClose = true;
						success = true;
						break;
					default:
						datToDisplay += "GENERIC ERROR: " + data + "</b>";
						noWriteClose = true;
						break;
				}
				if (!noWriteClose)
					datToDisplay += " Please try again.</b>";
				
				$('#ajax-panel').html('' + datToDisplay);
			},
		error:
			function()
			{
				$('#ajax-panel').html('<p class="error"><strong>Oops! Registration failed! Potential server issue.</strong> Try that again in a few moments.</p>');
			}
	});
	
	return false;
}

/*
	var request = $.ajax(
	{
		type: "GET",
		url: "/getSalt.php",
		data: "r=" + Math.random(),
		beforeSend:
			function()
			{
				// this is where we append a loading image
				$('#ajax-panel').html('<div class="loading"><img src="/images/loading.gif" alt="Loading..." /></div>');
			},
		success: 
			function(data) 
			{
				var salt = data;
				console.log(salt);
				$('#ajax-panel').empty();
				$('#ajax-panel').html('<p class="success">' + salt + '</p>');
			},
		error:
			function()
			{
				$('#ajax-panel').html('<p class="error"><strong>Oops! Registration failed!</strong> Try that again in a few moments.</p>');
			}
	});
*/