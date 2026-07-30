<?php 

class User {
    public $firstName;
    public $lastName;
    public $email;
    public $password;

    private $host = "mysql.railway.internal";
    private $db_login = "root";
    private $db_password = "root";
    private $db_name = "railway";

    public $connect;

    function __construct($firstName = '', $lastName = '', $email = '', $password = '') {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;

        // Railway environment variables or default to Localhost
        $host     = getenv('MYSQLHOST')     ?: $this->host;
        $user     = getenv('MYSQLUSER')     ?: $this->db_login;
        $db_pass  = getenv('MYSQLPASSWORD') ?: $this->db_password;
        $db_name  = getenv('MYSQLDATABASE') ?: $this->db_name;
        $port     = getenv('MYSQLPORT')     ?: 3306;

        $this->connect = mysqli_connect($host, $user, $db_pass, $db_name, (int)$port);

        if (!$this->connect) {
            die("Տվյալների բազայի միացման սխալ: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->connect, "utf8mb4");
    }

    function Register($firstName, $lastName, $email, $password) {
        if ($firstName == "" || $email == "" || $password == "") {
            echo "<script>alert('Լրացրու բոլոր պարտադիր դաշտերը։');</script>";
            return false;
        } else {
            $connect = $this->connect;
            $readsql = "SELECT * FROM `users` WHERE `email` = '$email'";
            $query = mysqli_query($connect, $readsql);

            if (mysqli_num_rows($query) > 0) {
                echo "<script>alert('Այս էլ փոստը արդեն գրանցված է։');</script>";
                return false;
            } else {
                // 🔐 Գաղտնաբառի հեշավորում DB-ում ապահով պահպանելու համար
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO `users`(`firstName`, `lastName`, `email`, `password`) 
                        VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
                $query = mysqli_query($connect, $sql);

                if ($query) {
                    // Alert-ից հետո օգտատիրոջը տեղափոխում ենք login.php էջ
                    echo "<script>
                        alert('Ձեր գրանցումը հաջողությամբ կատարվել է։ Մուտք գործեք');
                        window.location.href = 'login.php';
                    </script>";
                    return true;
                } else {
                    echo "<script>alert('Տեղի է ունեցել սխալ` գրանցման ժամանակ:');</script>";
                    return false;
                }
            }
        }
    }

    function Login($email, $password) {
        if ($email == "" || $password == "") {
            echo "<div class='alert alert-danger'>Լրացրու բոլոր դաշտերը։</div>";
            return false;
        } else {
            $connect = $this->connect;
            
            // 1. Փնտրում ենք օգտատիրոջը ըստ email-ի
            $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
            $query = mysqli_query($connect, $sql);

            if (mysqli_num_rows($query) == 1) {
                $user = mysqli_fetch_assoc($query);

                // 2. Ստուգում ենք մուտքագրված գաղտնաբառը DB-ի հեշավորված գաղտնաբառի հետ
                if (password_verify($password, $user['password'])) {
                    return true;
                } else {
                    return false; // Գաղտնաբառը սխալ է
                }
            } else {
                return false; // Email-ը գոյություն չունի
            }
        }
    }

    function ReadByUser($email) {
        $connect = $this->connect;
        $readsql = "SELECT * FROM `users` WHERE `email`='$email'";
        $query = mysqli_query($connect, $readsql);
        if ($query && mysqli_num_rows($query) > 0) {
            return mysqli_fetch_assoc($query);
        } else {
            return NULL;
        }
    }

    function getUserNotes($user_id) {
        $notes = [];
        $connect = $this->connect;
        $sql = "SELECT * FROM `notes` WHERE `user_id` = '$user_id' ORDER BY id DESC";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $notes[] = $row;
            }
        }
        return $notes;
    }

    function getNoteById($id) {
        $connect = $this->connect;
        $sql = "SELECT * FROM `notes` WHERE `id` = '$id'";
        $result = mysqli_query($connect, $sql);
        return $result;
    }

    function addNote($user_id, $title, $text, $content = null) {
        if ($title == "" || $text == "") {
            echo "<div class='alert alert-danger'>Լրացրու բոլոր դաշտերը։</div>";
            return false;
        } else {
            $connect = $this->connect;
            $sql = "INSERT INTO `notes`(`user_id`, `title`, `text`) VALUES ('$user_id','$title','$text')";
            $result = mysqli_query($connect, $sql);
            return $result;
        }
    }

    // ՈՒՂՂՎԱԾ. text='$text' (նախկինում title était)
    function editNote($id, $title, $text, $content = null) {
        if ($title == "" || $text == "") {
            echo "<div class='alert alert-danger'>Լրացրու բոլոր դաշտերը։</div>";
            return false;
        } else {
            $connect = $this->connect;
            $sql = "UPDATE `notes` SET `title`='$title', `text`='$text' WHERE `id` = '$id'";
            $result = mysqli_query($connect, $sql);
            return $result;
        }
    }

    // ԱՎԵԼԱՑՎԱԾ. Ջնջելու ֆունկցիա
    function deleteNote($id, $user_id) {
        $connect = $this->connect;
        $sql = "DELETE FROM `notes` WHERE `id` = '$id' AND `user_id` = '$user_id'";
        $result = mysqli_query($connect, $sql);
        return $result;
    }
}