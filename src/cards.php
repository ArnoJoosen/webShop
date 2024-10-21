<?php

function renderCategoryCard($name, $imageId = null) {
    $imageUrl = $imageId
        ? "https://picsum.photos/id/{$imageId}/300/200"
        : "https://picsum.photos/300/200";
    ?>
    <a class="card text-decoration-none" style="width: 18rem;" href="#">
        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
        </div>
    </a>
    <?php
}

function renderProductCard($name, $description, $price, $imageId = null) {
    $imageUrl = $imageId
        ? "https://picsum.photos/id/{$imageId}/300/200"
        : "https://picsum.photos/300/200"; ?>
    <div class="card" style="width: 18rem;">
        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars(
                $description
            ); ?></p>
            <p class="card-text"><strong>Price: $<?php echo number_format(
                $price,
                2
            ); ?></strong></p>
            <a href="#" class="btn btn-primary">Add to Cart</a>
        </div>
    </div>
    <?php
}
?>
