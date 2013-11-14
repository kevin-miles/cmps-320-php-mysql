<?php
if(isset($_POST['price']) && isset($_POST['quantity']) && isset($_POST['discount']) && isset($_POST['tax']) && isset($_POST['shipping']) && isset($_POST['payments']))
{

	include 'index.php'; //display form
	
	$price = 	$_POST['price'];
	$quantity = $_POST['quantity'];
	$discount =	$_POST['discount'];
	$tax = 	$_POST['tax'];
	$shipping = $_POST['shipping'];
	$payments = $_POST['payments'];
	
	$error = false; //start with no errors
	//error check for non-numeric values
	if(!is_numeric($price) || !is_numeric($quantity) || !is_numeric($discount) || !is_numeric($tax) || !is_numeric($shipping) || !is_numeric($payments))
	{
		echo '<div class="error">ERROR: Please enter a numeric value for every input.</div>';
		$error = true;
	}
	//error check for negative values
	if (($price < 0) || ($quantity < 0) || ($discount < 0) || ($tax < 0) || ($shipping < 0) || ($payments < 0))
	{
		echo '<div class="error">ERROR: All input needs to be positive in value. No negative numbers allowed.</div>';
		$error = true;
	}

	if($error == true) { exit(); }//if error was found, exit program now
	
	
	//no errors found, resume calculations
	//output user input
	echo '<div class="output">You have selected to purchase:<br><span class="bold">' .
		 $quantity . '</span> widget(s) at<br><span class="bold">' .
		 $price . '</span> price each plus<br><span class="bold">' . 
		 $discount . '</span> discount<br><span class="bold">' . 
		 $shipping . '</span> shipping cost and a<br><span class="bold">' . 
		 $tax . '%</span> tax rate</div>';
		 
		 
	$sum = ($quantity * $price) - $discount + $shipping; //calculate the sum of user input
	$taxamount = ($tax/100) * $sum; //find the amount of tax by multiplying sum by the rate
	//we divide $tax by 100 to get the actual decimal rate
	$sum += $taxamount; //add tax amount to total sum
	
	
	echo '<div class="output">After your <span class="bold">' .
		 $discount . ' </span>discount,<br> the total cost is <span class="bold">$' . $sum . '.</span></div>';
		 
	
	echo '<div class="output">Divided over <span class="bold">' .
		 $payments . ' monthly payments</span>,<br> that would be <span class="bold">$' . number_format(($sum/$payments),2) . '.</span></div>';
}

?>