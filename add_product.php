<?php
include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $price = $_POST['buying_price'];
    $date = $_POST['buying_date'];
    $purchased_by = $_POST['purchased_by'];
    $supplier = $_POST['supplier'];

    $check = $conn->prepare("SELECT id FROM products WHERE id=?");
    $check->bind_param("s", $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "<p style='color:red;'> Error: Product ID already exists!</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (id, name, quantity, category, price, date, purchased_by, supplier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisssss", $id, $name, $quantity, $category, $price, $date, $purchased_by, $supplier);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'> Product added successfully!</p>";
        } else {
            $message = "<p style='color:red;'> Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $check->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="homepage">


<header>
    <div class="container">
        <h1>Warehouse Inventory Management</h1>
        <nav>
            <a href="index.html"> Home</a>
            <a href="add_product.php"> Add Product</a>
            <a href="search_inventory.php"> Search Inventory</a>
            <a href="view_inventory.php"> View Inventory</a>
        </nav>
    </div>
</header>

<section>
    <div class="container">
        <h2>Add New Product</h2>
        <?php echo $message; ?>
        <form method="post" class="form-card">
            <label>Product ID:</label>
            <input type="text" name="product_id" required>

            <label>Product Name:</label>
            <input type="text" name="product_name" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" required>

            <label>Category:</label>
            <select name="category">
                <option>Electronics</option>
                <option>Furniture</option>
                <option>Clothing</option>
                <option>Food</option>
            </select>

            <label>Buying Price:</label>
            <input type="number" name="buying_price" step="0.01" required>

            <label>Buying Date:</label>
            <input type="date" name="buying_date" required>

            <label>Purchased By:</label>
            <div class="radio-group">
                <label><input type="radio" name="purchased_by" value="John" required> John</label>
                <label><input type="radio" name="purchased_by" value="Sarah"> Sarah</label>
                <label><input type="radio" name="purchased_by" value="Mike"> Mike</label>
                <label><input type="radio" name="purchased_by" value="Anna"> Anna</label>
            </div>

            <label>Supplier:</label>
            <select name="supplier">
                <option>ABC Suppliers</option>
                <option>XYZ Traders</option>
                <option>Global Goods</option>
                <option>FastSupply Co</option>
            </select>

            <button type="submit">Add Product</button>
        </form>
    </div>
</section>

</body>
</html>
