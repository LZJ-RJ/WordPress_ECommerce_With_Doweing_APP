<h3><?=$current_category->name;?></h3>
<?php
echo do_shortcode('[product_category paginate="1" category="'.$current_category->slug.'" per_page="36" columns="6" orderby="'.$_GET['orderby'].'" order="asc" operator="in"]');
