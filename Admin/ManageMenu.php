<?php 
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
    $con =  new Database();
    $getCategory = $con->viewCategory();
    
    if(isset($_POST["AddCat"])){
        
        $InputCategory = $_POST["categoryInput"];
        $AddCategory = $con->AddCategory($InputCategory);
    }
    if(isset($_POST['deleteCategory'])){
        $categoryID = $_POST["delete_id"];
        $categoryName = $_POST["delete_name"];
        try{
        $deleteCategory = $con->deleteCategory($categoryID);
        $_SESSION['success_message'] = $categoryName . 'has been deleted';
        header('Location: ManageMenu.php');
        exit();
        }
        catch(Exception $e){
            $error_message = 'Cannot delete this book, It may have active loans or copies in use.';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Products</title>
    <style>
        .owner-name{
            font-family:'Brush Script MT', 'Brush Script Std', cursive;
            font-size: 20px;
        }
        .custom-btn{
            background-color:#E69B1A;
            height: 50px;
        }
        .custom-btn:hover{
            background-color:#BC7F15;
        }
        .sidebar {
            width: 350px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow: hidden; 
        }
    </style>
</head>
<body>  
<div class="d-flex vh-100">
    <?php renderAdminSidebar(); ?>

    <main class="w-100" style="margin-left: 350px; margin-top: 30px;">
        <?php if(isset($error_message)){ ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        </strong><?php echo $error_message;?>
        <button type="button" class="btn-close" data-bs-dismissible="alert" aria-label="Close"></button>
    </div>
    <?php } ?>
    <?php if(isset($_SESSION['success_message'])) {?>
    <div class="alert alert-success alert dismissible fade show" role="alert">
        <strong>Success!</strong> <?php echo $_SESSION['success_message'];?>
        <button type="button" class="btn-colse" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <?php 
        unset($_SESSION['success_message']);
    }?>

    <div class="container w-50 h-30 mt-3 text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <h3 class="fw-bold">Product Control</h3>
            <div class="row">
                <div class="col">
                    <p class="fw-light">Control Product and make some changes.</p>
                </div>
            </div>
    </div>
    <div class="row mt-5">
        <div class="col-sm-4 offset-md-1 ">
            <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <div class="card-body">
                    <h5 class="card-title">Add Items to the List.</h5>
                    <p class="small-muted mb-3">Sample form for the Authors table.</p>
                    <form action="" method="POST"> 
                        <div class="col-12 col-md-6">
                            <label class="form-label">Product Name</label>
                            <input class="form-control" name="productName" placeholder="Yummy Papa Dave" required />
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" name="price" placeholder="0.00" required />
                        </div>

                        <div class="col-12 col-md-6 position-relative">
                            <label class="form-label">Stock</label>
                            <input type="text" class="form-control" name="stocks" placeholder="0" required />
                        </div>


                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Available</a></li>
                                <li><a class="dropdown-item" href="#">Unavailable</a></li>
                            </ul>
                        </div>
                    
                        <div class="col-12 col-md-6 position-relative">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category" placeholder="e.g., Drinks/Burger" required />
                        </div>
                        
                        <div class="submit-btn mt-4 d-flex justify-content-center">
                            <input class="btn btn-outline-success" type="submit" value="Add Product">
                        </div>
                    </form>
                </div>
            </div>
        </div>

<div class="col-sm-6">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-body">
            <h5 class="card-title">Add New Category</h5>
            <p class="card-text">You can fill this form to add new category for the new items.</p>

            <form action="" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" name="categoryInput" id="floatingPassword" placeholder="Category" required>
                    <label for="floatingPassword">Category</label>
                </div>

                <div class="submit-btn mt-4 d-flex justify-content-center">
                    <input class="btn btn-outline-primary" name="AddCat" value="Add Category" type="submit">
                </div>
            </form>
        </div>
    </div>
    
    <div class="col mt-4 ">
    <div class="card e-3 w-100 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
    
    <table class="table table-hover text-center align-middle">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Category</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach($getCategory as $Category){

                
            ?>
            <tr>
                <td><?php echo $Category['Category_ID']; ?></td>
                <td><?php echo $Category['Category_Name']; ?></td>
                <td>
                    <button type="button" 
                            class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="">Edit</button>

                    <button type="button" 
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteTargetCategory"
                    data-bs-catId = "<?php echo $Category['Category_ID'];?>"
                    data-bs-category="<?php echo $Category['Category_Name'];?>">Delete</button>
                
                </td>
            </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
    
<!--Delete Category Modal-->
        <div class="modal fade" id="deleteTargetCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteMessage"></strong></p>
            </div>
            <div class="modal-footer">
                <form method="POST">
                    <input type="hidden" name="delete_id" id="delete_id">
                    <input type="hidden" name="delete_name" id="deleteName">
                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="deleteCategory" class="btn btn-primary">Complete Delete</button>
                </form>    
            </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</main>
</body>
<script>
const deleteCategoryModal = document.getElementById('deleteTargetCategory');

deleteCategoryModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    if(!button) return;


    const id = button.getAttribute('data-bs-catId') || '';
    const name = button.getAttribute('data-bs-category') || '';

    document.getElementById('delete_id').value = id;
    document.getElementById('deleteName').value = name;
    document.getElementById('deleteMessage').textContent = name;

});
</script>
<script src="../functions/window.js"></script>
</html>