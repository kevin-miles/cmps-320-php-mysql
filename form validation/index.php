<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Calculator</title>
<style type="text/css">
label { width: 250px; 
		display: inline-block; 
		text-align: left;}
#submit {	margin: 25px 0 0 0; 
			width: 200px;
			border: 1px solid #000;}
.output { padding: 25px; }
.bold { font-weight: bold; }
.form_element { display: block; }
.error {	 background-color: #990000;
			padding: 25px;
			margin: 25px;}
</style>
</head>
<body>
<form method="post" action="handle_calc.php">
<div class="form_element">
<label for="price">Price:</label>
<input type="text" name="price" id="price">
</div>
<div class="form_element">
<label for="quantity">Quantity:</label>
<input type="text" name="quantity" id="quantity">
</div>
<div class="form_element">
<label for="discount">Discount:</label>
<input type="text" name="discount" id="discount">
</div>
<div class="form_element">
<label for="shipping">Shipping method:</label>
<select name="shipping" id="shipping">
<option value="5.00">6-7 bus. days for $5</option>
<option value="10.00">2-3 days for $10</option>
<option value="20.00">1 day for $20</option>
</select>
</div>
<div class="form_element">
<label for="tax">Tax:</label>
<input type="text" name="tax" id="tax">(%)
</div>
<div class="form_element">
<label for="payments">Number of payments to make:</label>
<input type="text" name="payments" id="payments">
</div>
<input type="submit" id="submit" value="CALCULATE">
</form>
</body>
</html>