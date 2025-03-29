<?php
session_start();
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
 
require '../PHPMailer/Exception.php'; 
require '../PHPMailer/PHPMailer.php'; 
require '../PHPMailer/SMTP.php'; 

Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	require 'db_connect.php';

    $this->db = $conn;
	}

	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	/*Admin*/
	function login_admin(){
		if(isset($_POST['email']) && isset($_POST['password'])){
			
			$email = $_POST['email'];
			$u_password = $_POST['password'];

			if ($stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE email = ? AND role = 'admin'")) {
				$stmt->bind_param('s', $email);
				$stmt->execute();
				$stmt->store_result();
			}

			if($stmt->num_rows > 0){
				$stmt->bind_result($id, $username, $h_password);
				$stmt->fetch();

				if(password_verify($u_password, $h_password)){
					$_SESSION['login_admin_id'] = $id;
					$_SESSION['login_admin_name'] = $username;
					return 200;
				} else {
					return 202;
				}
			} else {
				return 201;
			}			
		}
	}
	
	function logout_admin(){
		unset ($_SESSION['login_admin_id']);
		unset($_SESSION['login_admin_name']);
		header("location:login.php");
	}

	/*Authors*/
	function save_author(){	

		$id = $_POST['id'];	
		$name = $_POST['name'];
		$bio = $_POST['bio'];		
		$date_created = date('Y-m-d G:i:s');
		
		$check = $this->db->prepare('SELECT * FROM authors WHERE id = ?');
		$check->bind_param('i', $id);
		$check->execute();
		$check->store_result();			

		if($check->num_rows>0){
			$update = $this->db->prepare('UPDATE authors SET name = ?, bio = ? WHERE id = ?');
			$update->bind_param('ssi', $name, $bio, $id);
			$result2 = $update->execute();

			if($result2){					
				return 204;
			} else {
				return 205;
			}
		} else {
			$isDeleted=false;
			$stmp = $this->db->prepare('INSERT INTO authors VALUES(?,?,?,?,?)');
			$stmp->bind_param('issss', $id, $name, $bio, $date_created, $isDeleted);
			$result3 = $stmp->execute();
			
			if($result3){
				return 200;
			} else {
				return 201;
			}
		}			
	}

	function delete_author(){
		$id = $_POST['id'];

		$stmp = $this->db->prepare('UPDATE  authors SET isDeleted = true WHERE id = ?');
		$stmp->bind_param('i', $id);
		$result = $stmp->execute();

		if($result){
			return 200;
		} else {
			return 201;
		}
	}

	/*Categories*/
	function save_category(){

		$id = $_POST['id'];
		$genre = $_POST['genre'];
		$des = $_POST['description'];
		$created_at = date('Y-m-d G:i:s');

		$check = $this->db->prepare('SELECT * FROM categories WHERE id = ?');
		$check->bind_param('i', $id);
		$check->execute();
		$check->store_result();
		
		if($check->num_rows>0){
			$update = $this->db->prepare('UPDATE categories SET genre = ?, description = ? WHERE id = ?');
			$update->bind_param('ssi', $genre, $des, $id);
			$result1 = $update->execute();

			if($result1){					
				return 204;
			} else {
				return 205;
			}
		} else {
			$isDeleted=false;
			$stmp = $this->db->prepare('INSERT INTO categories VALUES(?,?,?,?,?)');
			$stmp->bind_param('issss', $id, $genre, $des, $created_at, $isDeleted);
			$result2 = $stmp->execute();
			
			if($result2){
				return 200;
			} else {
				return 201;
			}
		}		
	}

	function delete_category(){
		$id = $_POST['id'];

		$stmp = $this->db->prepare('UPDATE categories SET isDeleted = true WHERE id = ?');
		$stmp->bind_param('i', $id);
		$result = $stmp->execute();

		if($result){
			return 200;
		}
	}

	/*Pulblishers*/
	function save_publisher(){

		$id = $_POST['id'];
		$appellation = $_POST['appellation'];
		$created_at = date('Y-m-d G:i:s');

		$check = $this->db->prepare('SELECT * FROM publisher WHERE id = ?');
		$check->bind_param('i', $id);
		$check->execute();
		$check->store_result();

		if($check->num_rows>0){
			$update = $this->db->prepare('UPDATE publisher SET appellation = ? WHERE id = ?');
			$update->bind_param('si', $title, $id);
			$result1 = $update->execute();

			if($result1){					
				return 204;
			} else {
				return 205;
			}
		} else {		
			$isDeleted=false;
			$stmp = $this->db->prepare('INSERT INTO publisher VALUES(?,?,?,?)');
			$stmp->bind_param('isss', $id, $appellation, $created_at, $isDeleted);
			$result2 = $stmp->execute();
			
			if($result2){
				return 200;
			}			
		}		
	}

	function delete_publisher(){
		$id = $_POST['id'];

		$stmp = $this->db->prepare('UPDATE publisher SET isDeleted = true WHERE id = ?');
		$stmp->bind_param('i', $id);
		$result = $stmp->execute();

		if($result){
			return 200;
		} else {
			return 201;
		}
	}

	/*Books*/
	function save_book(){

		$id = $_POST['id'];
		$isbn = $_POST['isbn'];
		$title = $_POST['title'];
		$year = $_POST['year'];
		$description = $_POST['description'];
		$price = $_POST['price'];
		$publisher = $_POST['publisher'];
    	$date_created = date('Y-m-d G:i:s');
		$image = "";
		$flag = "";

		$check = $this->db->prepare('SELECT * FROM books WHERE id = ?');
		$check->bind_param('i', $id);
		$check->execute();
		$check->store_result();			

		if($check->num_rows>0){

			if(!empty($_FILES['cover']['name'])){
				$filename = $_FILES['cover']['name'];			
				$destination = 'assets/uploads/' . $filename;
				$extension = pathinfo($filename, PATHINFO_EXTENSION);
				
				$file = $_FILES['cover']['tmp_name'];
				$size = $_FILES['cover']['size'];
				
				if(!in_array($extension, ['jpeg', 'jpg', 'png'])){
					$flag = 1;
				} elseif($size > 1000000){
					$flag = 2;
				} else {
					if(move_uploaded_file($file, $destination)){
						$image = $filename;
					}
					else{
						$flag = 3;
					}
				}    
			}

			if(empty($_FILES['cover']['name'])){
				$img = $this->db->query("SELECT image FROM books WHERE id=".$id);
				while($row=$img->fetch_assoc()){
					$image = $row['image'];
				}				
			}

			$result1 = "";
			if(($image == "" && $flag == "") || ($image != "" && $flag == "")){
				$update = $this->db->prepare('UPDATE books SET isbn = ?, title = ?, year = ?, description = ?, image = ?, price = ?, publisher_id = ? WHERE id = ?');
				$update->bind_param('sssssdii', $isbn, $title, $year, $description, $image, $price, $publisher, $id);
				$result1 = $update->execute();
			}		
			
			if(isset($_POST['authors'])){
				$data = array();
				foreach ($_POST['authors'] as $author){
					$data[] = $author;
				}
				$st = $this->db->prepare("UPDATE book_author SET author_id = ? WHERE book_id = ?");
				$this->db->begin_transaction();
				
				foreach ($data as $row){
					$st->bind_param("ii", $row, $id);
					$st->execute();
				}
				$this->db->commit();
			}

			if(isset($_POST['categories'])){
				$data = array();
				foreach($_POST['categories'] as $category){
					$data[] = $category;
				}
				
				$st = $this->db->prepare("UPDATE book_category SET category_id = ? WHERE book_id = ?");
				$this->db->begin_transaction();
				
				foreach($data as $row){
					$st->bind_param("ii", $row, $id);
					$st->execute();
				}
				$this->db->commit();          
			}

			if($result1){						
				return 204;
			} else {
				return $flag;
			}
		} else {						

			if(!empty($_FILES['cover']['name'])){
				$filename = $_FILES['cover']['name'];			
				$destination = 'assets/uploads/' . $filename;
				$extension = pathinfo($filename, PATHINFO_EXTENSION);
				
				$file = $_FILES['cover']['tmp_name'];
				$size = $_FILES['cover']['size'];
				
				if(!in_array($extension, ['jpeg', 'jpg', 'png'])){
					$flag = 1;
				} elseif($size > 1000000){
					$flag = 2;
				} else {
					if(move_uploaded_file($file, $destination)){
						$image = $filename;
					}
					else{
						$flag = 3;
					}
				} 
			}

			if(empty($_FILES['cover']['name'])){
				$image = "noPhoto.png";
			}

			$isDeleted=false;
			$result2 = "";
			if(($image == "" && $flag == "") || ($image != "" && $flag == "")){
				$stmp = $this->db->prepare('INSERT INTO books VALUES(?,?,?,?,?,?,?,?,?,?)');
				$stmp->bind_param('isssssdiss', $id, $isbn, $title, $year, $description, $image, $price, $publisher, $date_created, $isDeleted);
				$result2 = $stmp->execute();   
			}
			
			$last_book_id = "";
			if($result2){
				$last_book_id = $this->db->insert_id;
			}
	
			if($last_book_id != "" && isset($_POST['authors'])){
				$data = array();
				foreach ($_POST['authors'] as $author){
					$data[] = $author;
				}
				$st = $this->db->prepare("INSERT INTO book_author(book_id, author_id) VALUES(?,?)");
				$this->db->begin_transaction();
				
				foreach ($data as $row){
					$st->bind_param("ii", $last_book_id, $row);
					$st->execute();
				}
				$this->db->commit();
			}
			
			if($last_book_id != "" && isset($_POST['categories'])){
				$data = array();
				foreach($_POST['categories'] as $category){
					$data[] = $category;
				}
				
				$st = $this->db->prepare("INSERT INTO book_category(book_id, category_id) VALUES(?,?)");
				$this->db->begin_transaction();
				
				foreach($data as $row){
					$st->bind_param("ii", $last_book_id, $row);
					$st->execute();
				}
				$this->db->commit();          
			}			
			if($result2){					
				return 200;
			} else {			
				return $flag;
			}		
		}				
	}

	function delete_book(){
		$id = $_POST['id'];

		$stmp = $this->db->prepare('UPDATE books SET isDeleted = true WHERE id = ?');
		$stmp->bind_param('i', $id);
		$result = $stmp->execute();

		if($result){
			return 200;
		} else {
			return 201;
		}
	}	

	/*User*/
	function signup(){
		$id = $_POST['id'];		
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$role = 'user';
		$date_created = date('Y-m-d G:i:s');

		if($id == NULL){
			if($stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?')){
				$stmt->bind_param('s', $email);
				$stmt->execute();
				$stmt->store_result();
				if($stmt->num_rows>0){
					return 201;
				} else {
					if($stmt = $this->db->prepare('INSERT INTO users(email, username, password, role, created_at) VALUES(?,?,?,?,?)')){
						$password = password_hash($password, PASSWORD_DEFAULT);
						$stmt->bind_param('sssss', $email, $username, $password, $role, $date_created);
						if($stmt->execute()){
							$admin_email = $this->db->query("SELECT email FROM users WHERE role='admin'");
							while($row=$admin_email->fetch_assoc()){
								$ad_email = $row['email'];
							}
							$subject='Welcome to bookstore "Atina"!';
							$name='Bookstore "Atina"';
							$body='<p>Welcome to bookstore "Atina" - the best online bookstore in entire Bulgarian internet!</p><p>Here you can find your favorite books on good prices!</p> 
							<p>Enjoy shopping!</p>';
							$this->send_email($ad_email, $name, $email, $subject, $body);													
							return 200;
						}
					} else {
						return 202;
					} 			
				}
				$stmt->close();
			} else {
				return 202;
			}
		} else {
			if($password == ""){
				$stmt = $this->db->prepare('UPDATE users SET email = ?, username = ? WHERE id = ?');
				$stmt->bind_param('ssi', $email, $username, $id);
				if($stmt->execute()){
					$_SESSION['login_name'] = $username;
					return 204;
				}
			} else {
				$stmt = $this->db->prepare('UPDATE users SET email = ?, username = ?, password = ? WHERE id = ?');
				$password = password_hash($password, PASSWORD_DEFAULT);
				$stmt->bind_param('sssi', $email, $username, $password, $id);
				if($stmt->execute()){
					$_SESSION['login_name'] = $username;
					return 204;
				}
			}
		}		
	}

	function login_user(){
		if(isset($_POST['email']) && isset($_POST['password'])){

			$email = $_POST['email'];
			$u_password = $_POST['password'];

			if ($stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE email = ? AND role = 'user'")) {
				$stmt->bind_param('s', $email);
				$stmt->execute();
				$stmt->store_result();
			}

			if($stmt->num_rows > 0){
				$stmt->bind_result($id, $username, $h_password);
				$stmt->fetch();

				if(password_verify($u_password, $h_password)){
					$_SESSION['login_id'] = $id;
					$_SESSION['login_name'] = $username;
					return 200;
				} else {
					return 202;
				}
			} else {
				return 201;
			}			
		}
	}

	function logout_user(){
		unset ($_SESSION['login_id']);
		unset($_SESSION['login_name']);
		header("location:../index.php");
	}

	/*Send email to admin*/	
	function send_feedback(){
		
		$name = $_POST['name'];
		$sender_email = $_POST['email'];
		$subject = $_POST['subject'];
		$body = $_POST['message'];

		$admin_email = $this->db->query("SELECT email FROM users WHERE role='admin'");
		while($row=$admin_email->fetch_assoc()){
			$ad_email = $row['email'];
		}				

		if($this->send_email($sender_email, $name, $ad_email, $subject, $body)) { 
			return 200;
		}	
	}

	function send_email($sender_email, $name, $res_email, $subject, $body){
		$mail = new PHPMailer; 
 
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'georgeivanov720@gmail.com'; 
		$mail->Password = 'Coccao_007!';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;                    
				
		$mail->setFrom($sender_email, $name); 		
		$mail->addAddress($res_email); 
		$mail->isHTML(true); 				
		$mail->Subject = $subject; 	
		$mail->Body  = $body;
		
		if($mail->send()) { 
			return true;
		}
	}	
	
	/*Manage cart*/
	function add_to_cart(){
		if(isset($_POST["book_id"])) {
			$_SESSION['book_id'] = $_POST['book_id'];
			$qty = $_POST['qty'];
			$previousQty = 0;
			if (isset($_SESSION['qty1'])) {		
				$previousQty = $_SESSION['qty1'];
			}			
			$_SESSION["qty1"] = $previousQty + $qty;			
		}
		if (isset($_POST["book_id"])) {
			foreach ($_POST as $key => $value) {
				$product[$key] = filter_var($value, FILTER_SANITIZE_STRING);
			}
			$statement = $this->db->prepare("SELECT title, price FROM books WHERE id=? LIMIT 1");
			$statement->bind_param('i', $product['book_id']);
			$statement->execute();
			$statement->bind_result($title, $price);
			
			while ($statement->fetch()) {
				$product["title"] = $title;
				$product["price"] = $price;
				if (isset($_SESSION["products"])) {
					if (isset($_SESSION["products"][$product['book_id']])) {
						$_SESSION["products"][$product['book_id']]["book_qty"] = $_SESSION["products"][$product['book_id']]["book_qty"] + $_POST["qty"];
					} else {
						$_SESSION["products"][$product['book_id']] = $product;
					}
				} else {
					$_SESSION["products"][$product['book_id']] = $product;
				}
			}
		}
		return 200;
	}

	function remove_from_cart(){
		$removeBookId = $_POST['book_id'];
		foreach ($_SESSION["products"] as $product){
			if($removeBookId == $product["book_id"]){
				$qty = $product["qty"];
			}
		}

		if (isset($_SESSION['products'][$removeBookId])) {
			unset($_SESSION['products'][$removeBookId]);
		}
		
		$previousQty = 0;
		if (isset($_SESSION['qty1'])) {		
			$previousQty = $_SESSION['qty1'];
		}			
		$_SESSION["qty1"] = $previousQty - $qty;
		return 200;
	}

	function decrease_quantity_in_cart(){
		$decreaseQuantityBookId = $_POST['book_id'];		
		if (isset($_SESSION['products'])) {
			if (isset($_SESSION['products'][$decreaseQuantityBookId])) {
				$currentQuantity = $_SESSION['products'][$decreaseQuantityBookId]['qty'];
				$_SESSION['products'][$decreaseQuantityBookId]['qty'] = $currentQuantity - 1;
			}
		}
		
		$previousQty = 0;
		if (isset($_SESSION['qty1'])) {		
			$previousQty = $_SESSION['qty1'];
		}			
		$_SESSION["qty1"] = $previousQty - 1;

		return 200;
	}

	function increase_quantity_in_cart(){
		$increaseQuantityBookId = $_POST['book_id'];		
		if (isset($_SESSION['products'])) {
			if (isset($_SESSION['products'][$increaseQuantityBookId])) {
				$currentQuantity = $_SESSION['products'][$increaseQuantityBookId]['qty'];
				$_SESSION['products'][$increaseQuantityBookId]['qty'] = $currentQuantity + 1;
			}
		}
		
		$previousQty = 0;
		if (isset($_SESSION['qty1'])) {		
			$previousQty = $_SESSION['qty1'];
		}			
		$_SESSION["qty1"] = $previousQty + 1;

		return 200;
	}

	/*Making order*/ 
	function make_order(){
		$id = NULL;
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$town = $_POST['town'];
		$address = $_POST['address'];
		$user_id = $_SESSION['login_id'];

		$flag = false;
		if(isset($_SESSION['login_id'])){
			$check  = $this->db->query("SELECT * FROM customers WHERE user_id =".$_SESSION['login_id']);			
			if($check->num_rows>0){
				$stmt = $this->db->prepare("UPDATE customers SET name = ?, tel = ?, email = ?, city = ?, address = ? WHERE user_id =" . $_SESSION['login_id']);
				$stmt->bind_param('sssss', $name, $phone, $email, $town, $address);
				$result = $stmt->execute();
				if($result){
					$flag=true;
				}
			} else {
				$stmt = $this->db->prepare("INSERT INTO customers(id, name, tel, email, city, address, user_id) VALUES(?,?,?,?,?,?,?)");
				$stmt->bind_param('isssssi', $id, $name, $phone, $email, $town, $address, $user_id);
				$result = $stmt->execute();
				if($result){
					$flag=true;;
				}
			}			
		}

		if($result){
			$last_customer_id = $this->db->query("SELECT id FROM customers WHERE user_id=".$user_id);
			while($row=$last_customer_id->fetch_assoc()){
				$customer_id = $row['id'];
			}
		}

		$order_flag=false;	
		foreach ($_SESSION["products"] as $product){
			$book_id = (int) $product['book_id'];
			$qty = (int) $product['qty'];
			if($stmt = $this->db->query("INSERT INTO orders(book_id, customer_id, qty) VALUES($book_id, $customer_id, $qty)")){
				$order_flag=true;
				$admin_email = $this->db->query("SELECT email FROM users WHERE role = 'admin'");
				while($row=$admin_email->fetch_assoc()){
					$ad_email = $row['email'];
				}
				$subject='Your order is received!';
				$e_name='Bookstore "Atina"';
				$body='<p>Hello <b>'. $name .'</b>, thank you for your order! After short processing from our employee, the order wil be send to you!</p>';				
				//$this->send_email($ad_email, $e_name, $email, $subject, $body);
			}
		}
		unset($_SESSION['products']);
		$_SESSION['qty1'] = 0;
		if($flag && $order_flag){
			return 200;
		}
	}

	function edit_customer(){
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$town = $_POST['town'];
		$address = $_POST['address'];
		$user_id = $_SESSION['login_id'];

		$stmt = $this->db->prepare("UPDATE customers SET name = ?, tel = ?, email = ?, city = ?, address = ? WHERE user_id =" . $user_id);
		$stmt->bind_param('sssss', $name, $phone, $email, $town, $address);
		$result = $stmt->execute();
		if($result){
			return 200;
		}
	}
}