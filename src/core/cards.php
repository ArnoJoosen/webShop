<?php

function renderCategoryCard($name, $url, $imageId = null)
{
    $imageUrl = $imageId
        ? "https://picsum.photos/id/{$imageId}/300/200"
        : "https://picsum.photos/300/200"; ?>
        <div class="card text-decoration-none" style="width: 18rem;" onclick="onCategoryClick('<?php echo $url?>')">
            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
            </div>
        </div>
    <?php
}

function renderProductCard($name, $description, $price, $productId, $imageId = null) {
    $imageUrl = $imageId
        ? "https://picsum.photos/id/{$imageId}/300/200"
        : "https://picsum.photos/300/200";
        ?>
    <div class="card" style="width: 18rem;">
        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>">
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
