<?php
require_once('config.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <script src="https://js.stripe.com/v3/"></script>
  <style type="text/css">
  	.hidden{
  		display: none;
  	}
  </style>
</head>
<body>
	<div id="express-checkout-element">
	  <!-- Express Checkout Element will be inserted here -->
	</div>
	<div id="error-message">
	  <!-- Display error message to your customers here -->
	</div>

</body>
<script type="text/javascript">



	// See your keys here: https://dashboard.stripe.com/apikeys
	const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

	const options = {
	  mode: 'payment',
	  amount: 1099,
	  currency: 'usd',
	  // Customizable with appearance API.
	  appearance: {
	  	 variables: {
    		// This controls the border-radius of the rendered Express Checkout Element
		    borderRadius: '4px',
		  }
	  },

	};

	// Set up Stripe.js and Elements to use in checkout form
	const elements = stripe.elements(options);  

	// const expressCheckoutElement = elements.create('expressCheckout', {
	//   // Specify a type per payment method
	//   // Defaults to 'buy' for Google, 'plain' for Apple, and 'paypal' for PayPal
	//   buttonType: {
	//     googlePay: 'checkout',
	//     // applePay: 'check-out',
	//     // paypal: 'buynow',
	//   },
	//   // Specify a theme per payment method
	//   // Default theme is based on appearance API settings
	//   buttonTheme: {
	//     applePay: 'white-outline'
	//   },
	//   // Height in pixels. Defaults to 44. The width is always '100%'.
	//   buttonHeight: 55
	// });

	// Create and mount the Express Checkout Element
	const expressCheckoutElement = elements.create('expressCheckout');
	expressCheckoutElement.mount('#express-checkout-element');
	
	// Optional: If you're doing custom animations, hide the Element
	const expressCheckoutDiv = document.getElementById('express-checkout-element');
	expressCheckoutDiv.style.visibility = 'hidden';

	expressCheckoutElement.on('ready', ({availablePaymentMethods}) => {
		console.log(availablePaymentMethods);
	  if (!availablePaymentMethods) {
	    // No buttons will show
	  } else {
	    // Optional: Animate in the Element
	    expressCheckoutDiv.style.visibility = 'initial';
	  }
	});

	const handleError = (error) => {
	  const messageContainer = document.querySelector('#error-message');
	  messageContainer.textContent = error.message;
	}

	expressCheckoutElement.on('confirm', async (event) => {
	  const {error: submitError} = await elements.submit();
	  if (submitError) {
	    handleError(submitError);
	    return;
	  }

	  // Create the PaymentIntent and obtain clientSecret
	  const res = await fetch('/php-test/stripe-express-checkout/create-intent.php', {
	    method: 'POST',
	  });
	  console.log(res);
	  const {client_secret: clientSecret} = await res.json();

	  const {error} = await stripe.confirmPayment({
	    // `elements` instance used to create the Express Checkout Element
	    elements,
	    // `clientSecret` from the created PaymentIntent
	    clientSecret,
	    confirmParams: {
	      return_url: 'http://localhost/php-test/stripe-express-checkout/success.php',
	    },
	  });

	  if (error) {
	    // This point is only reached if there's an immediate error when
	    // confirming the payment. Show the error to your customer (for example, payment details incomplete)
	    handleError(error);
	  } else {
	    // The payment UI automatically closes with a success animation.
	    // Your customer is redirected to your `return_url`.
	  }
	});
</script>
</html>