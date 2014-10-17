<html>
    <head>
        <title>tester</title>

    </head>
    <body>
        <h1>User Authorization</h1>
        <form name="userInsertForm" method="POST" action="index.php?op=getToken">
            <label>Email</label>
            <input name="email" type="text">
            <br>
            <label>Password</label>
            <input name="password" type="text">
            <br>
            <label>Restaurant Id</label>
            <input name="restaurant_id" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Get Token">
        </form>
        <h1>Get Restaurant Id</h1>
        <form name="userInsertForm" method="POST" action="index.php?op=getRestaurantId">
            <input type="submit" name="mysubmit" value="Get Restaurant Id">
        </form>
        <h1>Update User Profile</h1>
        <form name ="userUpdateForm" method="POST" action="index.php?op=update&type=user&field=profile">
            <label>AuthToken</label>
            <input name="authToken" type="text">
            <br>
            <label>First Name</label>
            <input name="first_name" type="text">
            <br>
            <label>Last Name</label>
            <input name="last_name" type="text">
            <br>
            <label>Contact No.</label>
            <input name="phone" type="text">
            <br>
            <label>Street 1</label>
            <input name="address1" type="text">
            <br>
            <label>Street 2</label>
            <input name="address2" type="text">
            <br>
            <label>City</label>
            <input name="city" type="text">
            <br>
            <label>State</label>
            <input name="state" type="text">
            <br>
            <label>Zip Code</label>
            <input name="zip" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Update User Profile">
        </form>
        <h1>Create New User</h1>
        <form name="userInsertForm" method="POST" action="index.php?op=create&type=user">
            <label>Email</label>
            <input name="email" type="text">
            <br>
            <label>Password</label>
            <input name="password" type="text">
            <br>
            <label>First Name</label>
            <input name="first_name" type="text">
            <br>
            <label>Last Name</label>
            <input name="last_name" type="text">
            <br>
            <label>Contact No.</label>
            <input name="phone" type="text">
            <br>
            <label>Street 1</label>
            <input name="address1" type="text">
            <br>
            <label>Street 2</label>
            <input name="address2" type="text">
            <br>
            <label>City</label>
            <input name="city" type="text">
            <br>
            <label>State</label>
            <input name="state" type="text">
            <br>
            <label>Zip Code</label>
            <input name="zip" type="text">
            <br>
            <label>Restaurant Id</label>
            <input name="restaurant_id" type="text">
            <br>

            <input type="submit" name="mysubmit" value="Create User">
        </form>
        <h1>Change User Password</h1>
        <form name="userPasswordChange" method="POST" action="index.php?op=update&type=user&field=password">
            <label>AuthToken</label>
            <input name="authToken" type="text">
            <br>
            <label>New Password</label>
            <input name="password" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Change Password">
        </form>
<!--        <h1>Save Addresses</h1>
        <form name="saveAddress" method="POST" action="index.php?op=save&field=address">
            <label>AuthToken</label>
            <input name="authToken" type="text">
            <br>
            <label>Name Of Address</label>
            <input name="nameOfAddress" type="text">
            <br>
            <label>AddressOne</label>
            <input name="address1" type="text">
            <br>
            <label>AddressTwo</label>
            <input name="address2" type="text">
            <br>
            <label>City</label>
            <input name="city" type="text">
            <br>
            <label>Zip</label>
            <input name="zip" type="text">
            <br>
            <label>State</label>
            <input name="state" type="text">
            <br>
            <label>Phone</label>
            <input name="phone" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Save Address">
        </form>
        <h1>Get Addresses</h1>
        <form name="getAddress" method="POST" action="index.php?op=get&field=address">
            <label>AuthToken</label>
            <input name="authToken" type="text">
            <br>
            <label>Name Of Address</label>
            <input name="nameOfAddress" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Get Address">
        </form>
        <h1>Save Cards</h1>
        <form name="SaveCreditCard" method="POST" action="index.php?op=save&type=card">
            <label>AuthToken</label>
            <input name="authToken" type="text">
            <br>
            <label>Name</label>
            <input name="name" type="text">
            <br>
            <label>CCNO</label>
            <input name="ccno" type="text">
            <br>
            <label>CCV</label>
            <input name="ccv" type="text">
            <br>
            <label>Expiry Month</label>
            <input name="expmonth" type="text">
            <br>
            <label>Expiry Year</label>
            <input name="expyear" type="text">
            <br>
            <label>Type</label>
            <input name="type" type="text">
            <br>
            <label>Billing Address</label>
            <input name="bill_addr" type="text">
            <br>
            <label>Billing Address 2</label>
            <input name="bill_addr2" type="text">
            <br>
            <label>Billing City</label>
            <input name="bill_city" type="text">
            <br>
            <label>Billing State</label>
            <input name="bill_state" type="text">
            <br>
            <label>Billing Zip Code</label>
            <input name="bill_zip" type="text">
            <br>
            <label>Billing Country</label>
            <input name="bill_country" type="text">
            <br>
            <label>Billing Phone</label>
            <input name="bill_phone" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Save Card Info">
        </form>
        <h1>Delete Cards</h1>
        <form name="removeCreditCard" method="POST" action="index.php?op=delete&type=card">
            <label>AuthToken</label>
            <input name="authToken" type="text">
            <br>
            <label>Name</label>
            <input name="name" type="text">
            <br>
            <input type="submit" name="mysubmit" value="delete card">
        </form>-->
        <h1>Fetch Order</h1>
        <form name="GetOrders" method="POST" action="index.php?op=fetch&field=order">
            <label>OrderID</label>
            <input name="order_id" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Fetch Order">
        </form>
        <h1>Restaurant Details</h1>
        <form name="fetchRestaurantDetails" method="POST" action="index.php?op=fetch&field=restaurant">
            <label>Restaurant Id</label>
            <input name="restaurant_id" type="text">
            <br>
            <input type="submit" name="mysubmit" value="get restaurant detail">
        </form>
        <h1>New Order</h1>
        <form name="placeOrder" method="POST" action="index.php?op=new&type=order">
            <label>Auth Token: </label>
            <input name="authToken" type="text">
            <br>
            <label>Restaurant Id: </label>
            <input name="restaurant_id" type="text">
            <br>
            <label>Delivery Type (1 for delivery, 2 for pickup): </label>
            <input name="delivery_type" type="text">
            <br>
            <label>Payment Method (1 for credit card, 2 for cash): </label>
            <input name="payment_method" type="text">
            <br>
            <label>Credit Card No: </label>
            <input name="x_card_num" type="text">
            <br>
            <label>Expiry Date(mmdd): </label>
            <input name="x_exp_date" type="text">
            <br>
            <label>Item 1 id</label>
            <input name="items[0][item_id]" type="text">
            <label>Item 1 Quantity</label>
            <input name="items[0][quantity]" type="text">
            <br>
            <label>Item 2 id</label>
            <input name="items[1][item_id]" type="text">
            <label>Item 2 quantity</label>
            <input name="items[1][quantity]" type="text">
            <br>
            <input type="submit" name="mysubmit" value="place an order">
        </form>
<!--        <h1>Fetch Cards</h1>
        <form name="placeOrder" method="POST" action="index.php?op=fetch&type=card">
            <label>Auth Token</label>
            <input name="authToken" type="text">
            <br>
            <input type="submit" name="mysubmit" value="Fetch Card">
        </form>-->
    </body>
</html>