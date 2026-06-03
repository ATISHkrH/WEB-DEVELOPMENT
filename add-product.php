<?php include "../config/db.php";
if($_SESSION['user']['role']!='admin') die("Access Denied");

if(isset($_POST['add'])){
 $n=$_POST['n'];
 $p=$_POST['p'];
 $conn->query("INSERT INTO products (name,price) VALUES ('$n','$p')");
}
?>
<h3>Add Product</h3>
<form method="POST">
<input name="n">
<input name="p">
<button name="add">Add</button>
</form>