<?php
function renderProductCard($name, $description, $price, $productId, $imageUrl = null) {?>
    <div class="card" style="width: 18rem;">
        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>" width="286" height="150" style="object-fit: contain;">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars(
                $description
            ); ?></p>
            <p class="card-text"><strong>Price: <?php echo number_format(
                $price,
                2
            ); ?>€</strong></p>
            <form method="post" action="shoppingCart.php">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
    <?php
}
?>
