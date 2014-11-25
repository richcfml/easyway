<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script>
<script type="text/javascript" src="js/new-menu.js"></script>
<link rel="stylesheet" type="text/css" href="css/new_menu.css">
 <form method ="post" id="add_item_form"  enctype="multipart/form-data">
     <div id ="main_div">
<div class="Submenu_Heading">Add New Sub Menu</div>
<table>
    <tr>
        <td>
     <input type="text" id="item_name" name="item_name" style="margin-left: 107px;margin-top: 30px;width:360px;height: 36px;padding:8px" placeholder="Item Name">
     </td>
     <td>
     <input type="text" id="price" name="price" style="margin-left: 38px;margin-top: 30px;width: 237px;height: 36px;padding: 8px;" placeholder="Price(ex: <?=$currency?>10.00) " onblur="$('#price').attr('placeholder','Price(ex: <?=$currency?>10.00)');">
    </td>
    </tr>
    <tr><td>
        <textarea rows="4" cols="50" id="description" name="description" style="resize: none;margin-left: 107px;margin-top: 30px;width: 360px;height: 133px;padding: 8px;float: left;" placeholder="description of Item">

        </textarea>
        </td>
        <td>
            <div class="photo_div_before" id="show_photo_before" ><div style="text-align: center;"> Photo of item? </div>

    <div style="height: 75px;width: 76px;margin-left: 60;">  <img src ="img/camera.png" id="item_img_camera" style="width: 90%;height: 79%;margin-left: 11px;"/> </div>
<div style="text-align: center;text-align: center;margin-top: -20px;width: 150px;margin-left: 33px;"> (Click to upload or drag and drop file here) </div></div>
<div class="photo_div" id="show_photo" ><div style="text-align: center;"> Photo of item? </div>
               <img src ="" id="item_img" style="height: 72%;margin-left: 40px;width: 141px;margin-top: 9px;"/></div>
               <input name="userfile" type="file" id="userfile" size="30" style="margin-top: -117px;opacity: 0;float: right;margin-right: 516px;width: 238px;height: 116px;">
        </td>
    </tr>
</table>
   
<table id ="chk_area" style="float: left;margin-left: 107px;margin-top: 25px;width: 250px;">
    <tr><td><input type="checkbox" name ="type[]" id ="new" value="0"/>
            <div><img src="img/new_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
        <td><input type="checkbox" name ="type[]" id ="vegan" value="1"/>
 <img src="img/vegan_icon.png" style="margin-left: 27px;margin-top: -25px;"></td>
        </tr>
        <tr><td><input type="checkbox" name ="type[]" id ="POPULAR" value="2"/>
            <div><img src="img/POPULAR_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
        <td><input type="checkbox" name ="type[]" id ="nut_free" value="3"/>
 <img src="img/nutfree_icon.png" style="margin-left: 27px;margin-top: -25px;"></td>
        </tr>
        <tr><td><input type="checkbox" name ="type[]" id ="glutenfree" value="4"/>
            <div><img src="img/glutenfree_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
        <td><input type="checkbox" name ="type[]" id ="LOWFAT" value="5"/>
 <img src="img/LOWFAT_icon.png" style="margin-left: 27px;margin-top: -25px;"></td>
        </tr>
        <tr><td><input type="checkbox" name ="type[]" id ="vegetarian" value="6"/>
            <div><img src="img/vegetarian_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
        <td><input type="checkbox" name ="type[]" id ="spicy" value="7"/>
 <img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;"></td>
        </tr>
        <tr><td colspan="2"><input type="checkbox" name="type[]" id ="spicy1" value="8"/>
            <div><img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;">
            <img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
        </tr>
        <tr><td colspan="3"><input type="checkbox" name="type[]" id ="spicy2" value="9"/>
            <div><img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;">
            <img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;">
            <img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
        </tr>
</table>
    <div>
   <input type="submit" value="Add" name="btnAdd" class="btnadd" id="btnAdd" style="width: 10%;margin-top: 89px;">
</div>
     </div>
</form>