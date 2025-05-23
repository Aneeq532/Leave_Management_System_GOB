<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `leave_applications` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
    <form action="" id="update-status-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="approved_by" value="<?php echo $_settings->userdata('id') ?>">

        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select id="status" name="status" class="form-control rounded-0" onchange="toggleReasonField()">
                <option value="0" <?php echo (isset($status) && $status == 0) ? 'selected' : '' ?>>Pending</option>
                <option value="1" <?php echo (isset($status) && $status == 1) ? 'selected' : '' ?>>Approved</option>
                <option value="2" <?php echo (isset($status) && $status == 2) ? 'selected' : '' ?>>Deny</option>
                <option value="3" <?php echo (isset($status) && $status == 3) ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <div class="form-group" id="deny_reason_div" style="display: none;">
            <label for="deny_reason">Reason for Denial</label>
            <textarea name="deny_reason" id="deny_reason" rows="3" class="form-control rounded-0"><?php echo isset($deny_reason) ? $deny_reason : '' ?></textarea>
        </div>

        <div class="text-right">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<script>
function toggleReasonField() {
    const status = document.getElementById('status').value;
    const reasonDiv = document.getElementById('deny_reason_div');
    reasonDiv.style.display = (status == 2) ? 'block' : 'none';
}

$(function(){
    toggleReasonField(); // Initialize on load

    $('#update-status-form').submit(function(e){
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=update_status",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err)
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function(resp){
                if(typeof resp === 'object' && resp.status === 'success'){
                    location.href = "./?page=leave_applications";
                } else if(resp.status === 'failed' && !!resp.msg){
                    var el = $('<div>')
                        .addClass("alert alert-danger err-msg").text(resp.msg)
                    _this.prepend(el)
                    el.show('slow')
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                    end_loader()
                } else {
                    alert_toast("An error occurred", 'error');
                    end_loader();
                    console.log(resp)
                }
            }
        })
    });
});
</script>
