@extends('master')

@section('titel','API Key Request') 
@section('header','Request an API Key')

@section('inhoud')

<script type="text/javascript">
    
    let hash_api = "http://hasherapi.westeurope.azurecontainer.io/hash/sha256/";
    let email_check = "./email_in_use/";
    let store_user = "./add";
    let user_api = "./getAPI";
    
    /**
     * Check if the provided password is correct
     * @return {Boolean} true if the password is correct
     */
    function correctPassword()
    {
        let pass1 = document.getElementById("pass1").value;
        let pass2 = document.getElementById("pass2").value;
        
        if((pass1 === pass2))
        {
            if(pass1 !== "")
            {
                return true;
            }
            else
            {
                document.getElementById("feedback").innerHTML = "Please fill in a password";
                document.getElementById("feedback").style.color = 'red';
                return false;
            }
        }
        else
        {
            document.getElementById("feedback").innerHTML = "Given passwords are not the same";
            document.getElementById("feedback").style.color = 'red';
            return false;
        }
    }
    
    /**
     * Store a user in the database
     */
    function storeUser()
    {
        if(correctPassword())
        {
            let name = document.getElementById("name").value;
            let email = document.getElementById("mail").value;
            let password = document.getElementById("pass1").value;
            console.log(email_check + email);
            
            fetch(email_check + email,
            {
                method: 'GET',
                headers: 
                {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Csrf-Token': '{{csrf_token()}}'
                }
            }).then(response=>
            {
                if(response.ok)
                {
                    console.log(response);
                    return response.text();
                }
                else
                {
                    console.log(response);
                    return Promise.reject("User database not found");
                }
            }).then(response=>
            {
                if(response==="FALSE")
                {
                    let api_key_input = document.getElementById("mail").value;
                    fetch(hash_api + api_key_input)
                            .then(response=>
                    {
                        if(response.ok)
                        {
                            console.log(response);
                            return response.json();
                        }
                        else
                        {
                            console.log(response);
                            return Promise.reject("Local database data not found");
                        }
                    })
                    .then(response => 
                    {
                        let api_key = response.output;
                        console.log('received api key: ' + api_key);

                        fetch(store_user,
                        {
                            method: 'POST',
                            headers: 
                            {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Csrf-Token': '{{csrf_token()}}'
                            },
                            body: JSON.stringify({name:name,email:email,password:password, api_key:api_key})
                        }).then(response=>
                        {
                            if(response.ok)
                            {
                                console.log(response);
                                return response.text();
                            }
                            else
                            {
                                console.log(response);
                                return Promise.reject("User not stored");
                            }
                        }).then(response=>
                        {
                            if(response==="User succesfully created")
                            {
                                document.getElementById("feedback").innerHTML = "User was succesfully created";
                                document.getElementById("feedback").style.color = 'black';
                                alert("Api key is: " + api_key);
                            }
                        });
                    });
                }
            });
        }
    }
    
    /**
     * For the given email address and password, show the corresponding api key as an alert
     */
    function getAPIKey()
    {
        let pass = document.getElementById("pass").value;
        let email = document.getElementById("mail_check").value;
        fetch(user_api,
        {
            method: 'POST',
            headers: 
            {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Csrf-Token': '{{csrf_token()}}'
            },
            body: JSON.stringify({email:email,password:pass})
        }).then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.text();
            }
            else
            {
                console.log(response);
                alert("User not found");
                return Promise.reject("User not stored");
            }
        }).then(response=>
        {
            alert("API key is: " + response);
        });
        
    }
</script>
<div>
    <p>Fill in your name and email address, as well as a suitable password. If no credentials exist for this email address, a new account will be created and an API key generated.</p>
    <form>
        Name:<br>
        <input type="text" id="name" value="Name"> <br><br>
        Email:<br>
        <input type="text" id="mail" value="example@genericmailservice.com"><br><br>
        Password:<br>
        <input type="password" id="pass1" value=""><br><br>
        Confirm password:<br>
        <input type="password" id="pass2" value=""><br><br>
        
        <input type="button" value="Submit" onclick="storeUser()">
    </form>
</div>
<div id="feedback">
    
</div>
<br>
<br>
<br>
<div>
    <p>Give your email and password to get you api key, if you already have an account.</p>
    <form method="get" action="">
        Email:<br>
        <input type="text" id="mail_check" value="example@genericmailservice.com">
        Password:<br>
        <input type="password" id="pass" value="">        
        <input type="button" value="Submit" onclick="getAPIKey()">
    </form>
</div>

@stop