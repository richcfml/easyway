<?php
require_once("../classes/config.php");
/**
 * Description of loadMenu
 *
 * @author Asher
 */
class loadMenu {
   public function getCategories($menu_id)
   {
        $mSQL = "SELECT * FROM categories WHERE menu_id = " . $menu_id . " ORDER BY cat_ordering";

   }
}
?>
<?php mysqli_close($mysqli);?>
