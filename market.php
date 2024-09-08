<?php include_once "./assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    //?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}

if (isset($_GET['new'])) {?>
        <div class="page-container">
            <div class="market-head">
                <h3>New Market</h3>
                
                <form method="post">
                    <input type="text" name="name" placeholder="Market name" required>
                    <input type="text" name="description" placeholder="Description" required>
                    <select name="type" required>
                        <option value="art">Art</option>
                        <option value="clothing">Clothing</option>
                        <option value="electronics">Electronics</option>
                        <option value="food">Food</option>
                        <option value="furniture">Furniture</option>
                        <option value="jewelry">Jewelry</option>
                        <option value="toys">Toys</option>
                        <option value="vehicles">Vehicles</option>
                        <option value="other">Other</option>
                    </select>
                    <?php if ($rank > 5) {?>
                    <input type="checkbox" name="default" value="1"> System default
                    <?php }?>
                    <input type="submit" name="register" value="Register">
                </form>
            </div>
        </div>

        <script>
        </script>
<?php } else {?>
        <div class="page-container">
            <div class="market-head">
                <h3>Market browsing</h3>
                
                <?php if (!isset($_GET['category'])) {?>
                <div class="market-categories">
                    <a href="?category=art">Art</a>
                    <a href="?category=clothing">Clothing</a>
                    <a href="?category=electronics">Electronics</a>
                    <a href="?category=food">Food</a>
                    <a href="?category=furniture">Furniture</a>
                    <a href="?category=jewelry">Jewelry</a>
                    <a href="?category=toys">Toys</a>
                    <a href="?category=vehicles">Vehicles</a>
                    <a href="?category=other">Other</a>

                    <a href="?new">New market</a>
                </div>
                <?php } else {
                    $category = $_GET['category'];
                    $markets = $db->query("SELECT * FROM `markets` WHERE `type` = '$category'")->fetchAll();
                    foreach ($markets as $market) {?>
                    <div class="market-item">
                        <h3><?php echo $market['name'];?></h3>
                        <p><?php echo $market['description'];?></p>
                        <a href="?market=<?php echo $market['id'];?>">View</a>
                    </div>
                    <?php }
                }?>
            </div>
        </div>

        <script>
        </script>
<?php }?>
    </body>
</html>