<div class="brief-form-container">
  <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" class="brief-form" method="POST" enctype="multipart/form-data">
    <?php wp_nonce_field( 'brief_upload', 'brief_upload_nonce' ); ?>
    <label for="file">Choose file to upload</label> <br>
    <input type="file" name="file" id="file" accept=".doc, .docx, .pdf">
    <input type="hidden" name="action" value="upload_brief">
    <br><button>Submit</button>
  </form>
</div>