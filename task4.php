<?php

// cleaning input data
function cleaning($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errorMessages = array();

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // if empty and validation 

    $email = cleaning($_POST['email']);
    if(!empty($email)){
        $email_check = filter_var($email, FILTER_VALIDATE_EMAIL);
        if(!$email_check){
            $errorMessages['email']='email is not valid';
        }
    }else {
        $errorMessages['email']='email cant be empty';
    }
    $password = cleaning($_POST['password']);
    if(!empty($password)){
        $password_check = strlen($password);
        if($password_check < 5){
            $errorMessages['password']='password have to be longer than 5 characters';
        }
    }else {
        $errorMessages['password']='password cant be empty';
    }
    $url = cleaning($_POST['url']);
    if(!empty($url)){
        $url_check = filter_var($url,FILTER_VALIDATE_URL);
        if(!$url_check){
            $errorMessages['url']='please check your url';
        }
    }else {
        $errorMessages['url']='please insert your url';
    }
    //upload files
    $tmp_path = $_FILES['uploadedFile']['tmp_name'];
    $name = $_FILES['uploadedFile']['name']; 
    if(!empty($name)){
        $file_check = "pdf"; //will be array if the website allow more extensions
        $nameArray = explode("." , $name); 
        $fileExtension = strtolower($nameArray[1]);
        if($file_check != $fileExtension){
            $errorMessages['uploaded file'] = 'uploaded files can only be pdf';
        }else{
            $finalName = rand().time().'.'.$fileExtension;
            $disFolder = './uploads/';
            $disPath = $disFolder.$finalName;
            if(move_uploaded_file($tmp_path,$disPath)){
                //echo "thanks for your input";
            }else{
                $errorMessages['uploaded file']='uploaded file failed please try again';
            }
        }
        
    }else{
        $errorMessages['uploaded file'] = 'please upload your CV file';
    }
}
if(count($errorMessages) == 0){
    echo 'Valid Data';
}else{
    foreach($errorMessages as $key=> $value){
        echo '* '.$key.' : '.$value.'<br>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task 4</title>
</head>
<body>
    <form method = "POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="Please enter your email">
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" placeholder="Please enter your password">
        </div>
        <div>
            <label>LinkedIn Account</label>
            <input type="text" name="url" placeholder="Please enter your LinkedIn Account url">
        </div>
        <div>
            <label>CV Upload (PDF files only)</label>
            <input type="file" name="uploadedFile">
        </div>
        <button type="submit">Submit</button>
    </form>
</body>
</html>