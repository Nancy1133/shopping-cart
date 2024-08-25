<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增新產品</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php require_once 'nav.php'; ?>
    <h1>新增新產品</h1>
    <form id="productForm">
        <label for="name">產品名稱:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="price">產品價格:</label><br>
        <input type="number" id="price" name="price" required><br><br>
        
        <input type="submit" value="送出">
    </form>

    <div id="responseMessage"></div>

    <script>
        $(document).ready(function(){
            $("#productForm").on("submit", function(event){
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: "insert_product.php", // PHP script to process the form data
                    method: "POST",
                    data: $(this).serialize(), // Serialize form data
                    success: function(response){
                        $("#responseMessage").html(response); // Display success or error message
                        $("#productForm")[0].reset(); // Reset the form
                    }
                });
            });
        });
    </script>
</body>
</html>
