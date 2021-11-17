<?php
$vendor_category = get_term_by('slug', $parent->slug . '-category', 'product_cat');
$vendor_child = get_terms(
    array(
        'taxonomy' => 'product_cat',
        'parent' => $vendor_category->term_id,
        'hide_empty' => false,
    ));
?>
    <div class="vendor-category-sidebar">
        <div class="sidebar-title">
            <i class="fas fa-list-ul"></i><span>分類</span>
        </div>
    <?php
foreach($vendor_child as $item) {
    ?>
    <div class="sidebar-item">
        <img class="vendor-category-mobile-img" referrerpolicy="no-referrer" src="http://doweing.store/wp-content/uploads/2021/10/-icon-1-e1635227387634.jpg" style="">
        <div>
            <a class="item-link" href="<?=get_term_link($item->term_id, 'product_cat');?>"><span><?=$item->name?></span></a>
            <span class="item-des"><?=sizeof(wc_get_products(['category' => array($item->slug)]))?> 商品</span>
        </div>
    </div>
    <?php
}
?>
    </div>
    <div class="order-list">
        <span class="order-item <?=!isset($_GET['orderby']) || $_GET['orderby'] == 'menu_order'? 'selected':''?>" attr-orderby="menu_order">綜合排名</span>
        <span class="order-item <?=$_GET['orderby'] == 'date'? 'selected':''?>" attr-orderby="date">最新</span>
        <span class="order-item <?=$_GET['orderby'] == 'popularity'? 'selected':''?>" attr-orderby="popularity">最熱銷</span>
        <span class="order-item <?=$_GET['orderby'] == 'price'? 'selected':''?>" attr-orderby="price">價格</span>
    </div>
<?php
echo do_shortcode('[product_category paginate="1" category="'.$current_category->slug.'" per_page="24" columns="4" orderby="'.$_GET['orderby'].'" order="asc" operator="in"]');
