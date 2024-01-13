<style>
        body, h1, label, input, table, th, td, a, span {
            font-family: 'Arial', sans-serif;
        }
    </style>
<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
   
    $sql = "SELECT * FROM `topic_list` where `topic_id` = '{$_GET['id']}' and `user_id` = '{$_SESSION['user_id']}' ";
    $query = $conn->query($sql);
    $data = $query->fetchArray();

}

// Generate Manage topic Form Token
$_SESSION['formToken']['topic-form'] = password_hash(uniqid(), PASSWORD_DEFAULT);

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageDir = 'path/to/uploaded/images/'; // Replace with the actual path
        $imageName = uniqid('image_') . '_' . basename($_FILES['image']['name']);
        $imagePath = $imageDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $sql .= ", `image` = '$imageName'";
        } else {
            echo "Error uploading image.";
        }
    }
}
?>

<h1 class="text-center fw-bolder"><?= isset($data['topic_id']) ? "Update Topic Details" : "" ?></h1>
<hr class="mx-auto opacity-100" style="width:50px;height:3px">
<div class="col-lg-6 col-md-8 col-sm-12 col-12 mx-auto">
    <div class="card rounded-0">
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="topic-form" enctype="multipart/form-data" method="POST"> <!-- Add enctype attribute for file uploads -->
                    <input type="hidden" name="formToken" value="<?= $_SESSION['formToken']['topic-form'] ?>">
                    <input type="hidden" name="topic_id" value="<?= $data['topic_id'] ?? '' ?>">
                    <div class="mb-3">
                        <label for="title" class="text-body-tertiary">Your question to the community</label>
                        <input type="text" class="form-control rounded-0" id="title" name="title" required="required" autofocus value="<?= $data['title'] ?? "" ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="text-body-tertiary">Description of your problem</label>
                        <textarea rows="5" class="form-control rounded-0" id="description" name="description" required="required"><?= $data['description'] ?? "" ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="text-body-tertiary">Upload Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select rounded-0" required>
                            <option value="0" <?= isset($data['status']) && $data['status'] == 0 ? "selected" : "" ?>>Unpublished</option>
                            <option value="1" <?= isset($data['status']) && $data['status'] == 1 ? "selected" : "" ?>>Publish</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <div class="row justify-content-evenly">
                <button class="btn col-lg-4 col-md-5 col-sm-12 col-12 btn-primary rounded-0" form="topic-form" style="background-color: rgb(83, 178, 138);">Save</button>
                <a class="btn col-lg-4 col-md-5 col-sm-12 col-12 btn-secondary rounded-0" href='./?page=topic'>Cancel</a>
            </div>
        </div>
    </div>
</div>

<!-- Display uploaded image -->
<?php if (isset($data['image'])): ?>
    <div class="mb-3">
        <label class="text-body-tertiary">Uploaded Image</label>
        <img src="path/to/uploaded/images/<?= $data['image'] ?>" alt="Uploaded Image" class="img-fluid">
    </div>
<?php endif; ?>

<script>
    $(function(){
        $('#topic-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove();
            var _this = $(this);
            var _el = $('<div>');
            _el.addClass('pop_msg');
            _this.find('button').attr('disabled', true);
            $.ajax({
                url: './Master.php?a=save_topic',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                error: err => {
                    console.log(err);
                    _el.addClass('alert alert-danger');
                    _el.text("An error occurred.");
                    _this.prepend(_el);
                    _el.show('slow');
                    _this.find('button').attr('disabled', false);
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        if ('<?= $_GET['toview'] ?? "" ?>' == "") {
                            location.replace("./?page=topic");
                        } else {
                            location.replace("./?page=view_topic&id=<?= $data['topic_id'] ?? "" ?>");
                        }
                    } else {
                        _el.addClass('alert alert-danger');
                    }
                    _el.text(resp.msg);

                    _el.hide();
                    _this.prepend(_el);
                    _el.show('slow');
                    _this.find('button').attr('disabled', false);
                }
            });
        });
    });
</script>
