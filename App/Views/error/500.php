<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - forum
 * Last Updated - 11/09/2023
 */

use App\Framework\Http\View;

?>

<h1>Error 500</h1>
<p><?php if(isset(View::Bag()["error"])){echo View::Bag()["error"];}?></p>