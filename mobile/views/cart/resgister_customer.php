<h1>Customer information</h1>
    <input type="hidden" name="step" value="2">
    <div class="margintop normal">
      <div class="left">First Name:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="Name" tabindex="1" id="customer_name" name="customer_name"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Last Name:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="Name" tabindex="1" id="customer_last_name" name="customer_last_name"  />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">Phone:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="Phone Number" tabindex="2" id="customer_phone" name="customer_phone"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Email:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="Email Address" tabindex="3" id="customer_email" name="customer_email"  />
      </div>
      <div class="clear"></div>
    </div>

    <div class="margintop normal">
      <div class="left">Address:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="Address" tabindex="4" id="customer_address" name="customer_address"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">City:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="City" tabindex="5" id="customer_city" name="customer_city"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">State:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="State" tabindex="6" id="customer_state" name="customer_state"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Zip Code:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="Zip" tabindex="7" id="customer_zip" name="customer_zip"  />
      </div>
      <div class="clear"></div>
    </div>

    <div class="rightalign">
      <input type="submit" name="btntempcustomer" value=" Next "  class="button blue">
    </div>
    <script type="text/javascript">
$(function(){
$("#checkoutform").validate({
           rules: {
				customer_name: {required: true  },
				customer_last_name: {required: true},
				customer_phone: {required: true,minlength: 5},
				customer_email: {required: true,email:1},
				customer_address: {required: true,minlength: 3},
				customer_city: {required: true,minlength: 3},
				customer_state: {required: true,minlength: 2},
				customer_zip: {required: true,minlength: 2},
				 
           },
           messages: {
			     customer_name: {
					   		   required: "please enter your first name"
						   },
			 customer_last_name: {
					   		   required: "please enter your last name"
						 },
				customer_phone: {
					   required: "please enter your phone number",
					   minlength: "please enter a valid phone number"
                        
                   },
                   customer_email: {
							   required: "please enter your email address",
							   email: "please enter a valid email address"
                               },
		   		 customer_address: {
					   required: "please enter your street address",
					   minlength: "please enter a valid address"
                        
                   },
				 customer_city: {
					   required: "please enter your city",
					   minlength: "please enter a valid city"
                        
                   },
				 customer_state: {
					   required: "please enter your state",
					   minlength: "please enter a valid state"
                        
                   },
				customer_zip: {
					   required: "please enter your zip",
					   minlength: "please enter a valid zip"
                        
                   },
				
					   
           },
		   errorElement: "div",
       
          errorClass: "alert-error",
});
});
</script>