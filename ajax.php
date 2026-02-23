<?php
include 'db_connect.php';







if(isset($_GET['action'])){

    // SAVE
    if($_GET['action'] == 'save_department'){
        $name = $conn->real_escape_string($_POST['name']);
        $save = $conn->query("INSERT INTO departments (name) VALUES ('$name')");
        echo $save ? 1 : 0;
    }

    // UPDATE
    if($_GET['action'] == 'update_department'){
        $id = intval($_POST['id']);
        $name = $conn->real_escape_string($_POST['name']);
        $update = $conn->query("UPDATE departments SET name='$name' WHERE id=$id");
        echo $update ? 1 : 0;
    }

    // DELETE
    if($_GET['action'] == 'delete_department'){
        $id = intval($_POST['id']);

        // Optional: prevent delete if students exist
        $check = $conn->query("SELECT id FROM students WHERE department_id=$id LIMIT 1");
        if($check->num_rows > 0){
            echo 0;
            exit;
        }

        $delete = $conn->query("DELETE FROM departments WHERE id=$id");
        echo $delete ? 1 : 0;
    }

}




if(isset($_GET['action']) && $_GET['action'] == 'delete_student'){
    $id = intval($_POST['id']); // get the student ID
    $delete = $conn->query("DELETE FROM students WHERE id = $id"); // delete student
    if($delete) echo 1;
    else echo 0;
    exit; // stop execution
}
?>


<?php

ob_start();
date_default_timezone_set("Asia/Manila");


$action = $_GET['action'];

include 'admin_class.php';
if($action == 'delete_department'){
    if(isset($_POST['id'])){
        $id = intval($_POST['id']);
        $save = $crud->delete_department($id);
        if($save){
            echo 1;
        }else{
            echo 0;
        }
    }
    exit;
}

if($action == 'delete_student'){
    if(isset($_POST['id'])){
        $id = intval($_POST['id']);
        $save = $crud->delete_student($id);
        if($save){
            echo 1;
        }else{
            echo 0;
        }
    }
    exit;
}

$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'save_class'){
	$save = $crud->save_class();
	if($save)
		echo $save;
}
if($action == 'delete_class'){
	$save = $crud->delete_class();
	if($save)
		echo $save;
}
if($action == 'save_subject'){
	$save = $crud->save_subject();
	if($save)
		echo $save;
}
if($action == 'delete_subject'){
	$save = $crud->delete_subject();
	if($save)
		echo $save;
}
if($action == 'save_student'){
	$save = $crud->save_student();
	if($save)
		echo $save;
}
if($action == 'delete_student'){
	$save = $crud->delete_student();
	if($save)
		echo $save;
}
if($action == 'save_result'){
	$save = $crud->save_result();
	if($save)
		echo $save;
}
if($action == 'delete_result'){
	$save = $crud->delete_result();
	if($save)
		echo $save;
}
ob_end_flush();

?>
