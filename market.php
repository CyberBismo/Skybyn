<?php include_once "./assets/header.php";

if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
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
                        <option value="games">Games</option>
                        <option value="vehicles">Vehicles</option>
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
                    <div class="market-category" onclick="window.location.href='?category=art'">
                        <h4>Art</h4>
                        <p>Paintings, sculptures, and other art pieces.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=clothing'">
                        <h4>Clothing</h4>
                        <p>Shirts, pants, and other clothing items.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=electronics'">
                        <h4>Electronics</h4>
                        <p>Phones, computers, and other electronic devices.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=food'">
                        <h4>Food</h4>
                        <p>Meals, snacks, and other food items.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=furniture'">
                        <h4>Furniture</h4>
                        <p>Tables, chairs, and other furniture items.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=jewelry'">
                        <h4>Jewelry</h4>
                        <p>Necklaces, rings, and other jewelry items.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=toys'">
                        <h4>Toys</h4>
                        <p>Stuffed animals, action figures, and other toys.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=games'">
                        <h4>Games</h4>
                        <p>Board games, video games, and other games.</p>
                    </div>
                    <div class="market-category" onclick="window.location.href='?category=vehicles'">
                        <h4>Vehicles</h4>
                        <p>Cars, motorcycles, and other vehicles.</p>
                    </div>

                    <?php if (isset($_SESSION['user'])) {?>
                    <div class="market-category" onclick="window.location.href='?new'">
                        <h4>New Market</h4>
                        <p>Register a new market.</p>
                    </div>
                    <?php }?>
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