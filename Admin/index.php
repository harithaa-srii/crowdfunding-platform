<?php
require('config.php');
?>
<!DOCTYPE html>
<html lang="en" >
<form action="submit.php" method="post">
	<script 
		src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		data-key="<?php echo $publishableKey?>"
		data-amount="500"
		data-name="Crowdfund"
		data-description="Crowdfunding Platform desc"
		data-image="https://halephysio.co.uk/wp-content/uploads/2015/04/reiki.jpg"
		data-currency="inr"
		data-email="vaseegaran.n@gmail.com"
	>
	</script>
</form>
</html>