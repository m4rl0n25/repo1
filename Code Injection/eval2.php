
<?php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpClientInterface;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\TextType;

<?php

// Example 1: SQL Injection
$username = $_GET['username']; // Directly using user input in a query
$query = "SELECT * FROM users WHERE username = '$username'";
// BAD: Prone to SQL injection if $username contains malicious SQL code
$result = mysql_query($query); 

// Example 2: Cross-Site Scripting (XSS)
$name = $_GET['name'];
// BAD: Directly outputting user input without encoding
echo "Hello, " . $name; // If $name is "<script>alert('XSS!');</script>", it will execute

// Example 3: File Inclusion Vulnerability
$page = $_GET['page'];
// BAD: Allows arbitrary file inclusion
include($page . ".php"); // If $page is "../../../etc/passwd", it might expose sensitive data

// Example 4: Unsafe File Upload
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// BAD: No proper validation of file type or content
move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file); // Allows uploading

function new_http_param() {
    $r = new Request(
        $_GET,
        $_POST,
        [],
        $_COOKIE,
        $_FILES,
        $_SERVER
    );
    $code = $r->request->get("code");
    eval($code); 


?> 
