<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `crops_list` where crops_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="card">
<div class="card-header" style="background-color: rgb(83, 178, 138);">
        <h5 class="card-title"><?php echo isset($_GET['id'])? "Update" :"Create New" ?> Crops</h5>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="" id="crops-form">
                <input type="hidden" name="id" value="<?php echo isset($crops_id) ? $crops_id : '' ?>">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="control-label">season</label>
                                <input type="text" name="title" autofocus id="title" required class="form-control form-control-sm rounded-0" value="<?php echo isset($title) ? $title : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="control-label">Crops</label>
                                <select name="category_id" id="category_id" class="form-select form-select-sm rounded-0" required>
                                    <option <?php echo (!isset($category_id)) ? 'selected' : '' ?> disabled>Please Select Here</option>
                                    <?php
                                    $category = $conn->query("SELECT * FROM category_list order by `name` asc");
                                    while($row= $category->fetchArray()):
                                    ?>
                                        <option value="<?php echo $row['category_id'] ?>" <?php echo (isset($category_id) && $category_id == $row['category_id'] ) ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status" class="control-label">Status</label>
                                <select name="status" id="status" class="form-select form-select-sm rounded-0" required>
                                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Published</option>
                                    <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Unpublished</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="images" class="control-label">crops Image</label>
                                <input type="file" name="images" id="images" <?php echo !isset($crops_id)? "required" : "" ?> class="form-control form-control-sm rounded-0" accept="images/png, images/jpeg">
                            </div>
                            <div class="form-group">
                                <label for="planning" class="control-label">Planning</label>
                                <textarea name="planning" id="planning" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the all about here." data-height="30vh" required><?php echo isset($planning) ? $planning : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="land_preparation" class="control-label">All Abouts:</label>
                                <textarea name="land_preparation" id="land_preparation" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the soil type and soil pH here." data-height="30vh" required><?php echo isset($land_preparation) ? $land_preparation : '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="planting_stages" class="control-label">Soil Type And Soil pH:</label>
                                <textarea name="planting_stages" id="planting_stages" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the how to grow here." data-height="40vh" required><?php echo isset($planting_stages) ? $planting_stages : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="other_info" class="control-label">Crop Companions</label>
                                <textarea name="other_info" id="other_info" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the crop companions here." data-height="40vh" required><?php echo isset($other_info) ? $other_info : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer">
        <div class="col-12 d-flex justify-content-end">
            <div class="col-auto">
            <button class="btn btn-primary rounded-0 me-2" style="background-color: rgb(83, 178, 138);" form="crops-form">Save</button>
                <a class="btn btn-dark rounded-0" href="./?page=crops">Cancel</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#crops-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('.card-footer button').attr('disabled',true)
            $('.card-footer button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./../Actions.php?a=save_crops',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('.card-footer button').attr('disabled',false)
                     $('.card-footer button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('.card-footer').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($crops_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#page-container').animate({scrollTop:0},'fast')
                     $('.card-footer button').attr('disabled',false)
                     $('.card-footer button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>