<?php include "config/db.php";
if(isset($_SESSION['user']) && $_SESSION['user']['role']=='user'){
 $total=0;
 foreach($_SESSION['cart'] as $id=>$qty){
  $res=$conn->query("SELECT price FROM products WHERE id=$id");
  $p=$res->fetch_assoc();
  $total += $p['price']*$qty;
 }
 $conn->query("INSERT INTO orders (user_id,total) VALUES (".$_SESSION['user']['id'].",$total)");
 $_SESSION['cart']=[];
 echo "Order Placed!";
}
?>