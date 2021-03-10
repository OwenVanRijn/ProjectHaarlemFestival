
<html>
<title>Submit Form without Page Refresh - PHP/jQuery - TecAdmin.net</title>
<head>
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://tecadmin.net/demo/submit-form-without-refresh-php-jquery/js/submit.js"></script>
</head>
<body>
<form id="myForm" method="post">
    Name: <input name="name" id="name" type="text" /><br />
    Email: <input name="email" id="email" type="text" /><br />
    Phone No:<input name="phone" id="phone" type="text" /><br />
    Gender: <input name="gender" type="radio" value="male">Male
    <input name="gender" type="radio" value="female">Female<br />
    <input type="button" id="submitFormData" onclick="SubmitFormData();" value="Submit" />
</form>
<br />
Your data will display below..... <br />
==============================<br />
<div id="results">

</div>
</body>
</html>


<?Php

if(isset($_POST["name"])) {
    echo $_POST['name'] . "<br />";
    echo $_POST['email'] . "<br />";
    echo $_POST['phone'] . "<br />";
    echo $_POST['gender'] . "<br />";
    echo "==============================<br />";
    echo "All Data Submitted Successfully!";
}


?>
