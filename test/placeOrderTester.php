<?php
  include ('../model/all_products.php');
  $product_id = '14';
  $product = $products[$product_id];
  $title = "Order";


    error_reporting(1);
    session_start(); 

    if (!isset($_SESSION['username'])) {
      $_SESSION['msg'] = "You must log in first";
      //header('location: login.php');
    }
    if (isset($_GET['logout'])) {
      session_destroy();
      unset($_SESSION['username']);
      header("location: index.php");
    }


    if (isset($_POST['search'])) {
      $keywords = $_POST["keywords"];
    }
  

  include ('../controller/connection.php');


  $userID = -1;

  if (isset($_SESSION['username'])){
    $userQuery = 'SELECT * from users where username = "' . $_SESSION['username'] . '"';
    $userIDresult = mysqli_query($db,$userQuery);
    while ($userIDrow = mysqli_fetch_array($userIDresult, MYSQLI_ASSOC)) {
      $userID = $userIDrow["id"];
    };
    $userID = (int) $userID;
  }
  

    
   
   $btn_action = "payment.php";
   $btn_name = "Proceed to Checkout";
   include ('../controller/connection.php');
   $order_id = mysqli_insert_id($db);

   if (isset($_POST['place_order'])) {

    // connect to database
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $telephone = $_POST['telephone'];
    $phone = $_POST['phone'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $postCode = $_POST['postCode'];
    $item = $product_id;
    $total = $product["price"];

    $sql = "INSERT INTO orders (user, firstName, lastName, address, address2, telephone, phone, state, city, postCode, total, orderStatus) 
    VALUES ('$userID', '$firstName', '$lastName','$address','$address2','$telephone','$phone','$state','$city','$postCode', '$total','Processing')";

    $Query = mysqli_query($db, $sql); // store the submitted data to the database..

    $newOrderID = mysqli_insert_id($db);
    
    
    $sql = "INSERT INTO orderItems (userID, productID, orderID) 
    VALUES ('$userID', '$product_id', '$newOrderID')";

    $Query = mysqli_query($db, $sql);
    

    if ($Query) {
      
    }
    echo "New record has id: " . mysqli_insert_id($db);
    echo '<br>Order Test Passed';
    
  }



  ?>
 
<div class="container">

<form method="post" enctype="multipart/form-data">
  <div class="col-lg-8">
    <table class="table table-bordered">
       <tr class="warning">
                <td><h2>Delivery Address:</h2></td>
              </tr>

       <tr class="warning">
          <td>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <p>First Name:</p>
              <input type="text" name="first-name" value="Sabbir">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                      <p>Last Name:</p>
              <input type="text" name="last-name" value="Hossain">
            </div>
          </td>
        </tr>

        <tr class="warning">
          <td>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <p>Telephone:</p>
              <input type="text" name="telephone" value="627172389">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
               <p>Cell Phone:</p>
              <input type="text" name="phone" value="01521412024">
            </div>
          </td>
        </tr>

        <tr class="warning">
          <td>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <p>Address*:</p>
              <input type="text" name="address" class="col-lg-12 col-md-12 col-xs-12 col-sm-12" value="289/1, 3A, Gulbagh, Malibagh">
            </div>
          </td>
        </tr>

        <tr class="warning">
          <td>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <p>Address 2:</p>
              <input type="text" name="address2" class="col-lg-12 col-md-12 col-xs-12 col-sm-12" value="Dhaka 1217">
            </div>
          </td>
        </tr>

        <tr class="warning">
          <td>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <p>State:</p>
              <select name="state">
                <option value="Please Select">Please Select</option>
                <option value="Dhaka" selected>Dhaka</option>
                <option value="Chittagong">Chittagong</option>
                <option value="Khulna">Khulna</option>
                <option value="Barishal">Barishal</option>
                <option value="Mymensingh">Mymensingh</option>
                <option value="Sylhet">Sylhet</option>
                <option value="Rajshahi">Rajshahi</option>
                <option value="Rangpur">Rangpur</option>
            </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <span>City: </span>
              <input type="text" name="city" value="Dhaka">
              <br>
              <span>Post Code: </span>
              <input type="text" name="postCode" value="1217">
            </div>
          </td>
        </tr>
    </table>
  </div>





<!-- Calculation Panel -->
  <div class="col-lg-4">
    <br>
    <table class="table table-bordered">
      <thead>
        <tr class="warning">
          <td><b>PRODUCT DESCRIPTION</b></td>
          <td><b>PRICE</b></td>
        </tr>
      </thead>
      <tbody>
        <tr class="warning">
          <td>
            Cart Total
          </td>
          <td>
            BDT <span id="total-price"><?php echo $product["price"]?></span>
          </td>
        </tr>
        <tr class="warning">
          <td>
            Estimated VAT/CST
          </td>
          <td>
            BDT <span id="total-vat">*Included</span>
          </td>
        </tr>
        <tr class="warning">
          <td>
            Coupon Discount
          </td>
          <td>
            BDT <span id="total-coupon">0</span>
          </td>
        </tr>
        <tr class="warning">
          <td>
            Delivery Charge
          </td>
          <td>
            BDT <span id="delivery-charge">0</span>
          </td>
        </tr>
        <tr class="warning">
          <td>
            <b>Order Total</b>
          </td>
          <td>
            <b>BDT <span id="total-price"><?php echo $product["price"]?></span></b>
          </td>
        </tr>
      </tbody>
    </table>
      <button type="submit" name="place_order" class="btn btn-success center-block"><?php echo "Confirm and Place Order"?></button>
  </div>
</form>
  <!-- Calculation panel End -->
</div>