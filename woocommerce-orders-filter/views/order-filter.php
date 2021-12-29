<div class="wcof-form-container">


    <label class="wcof-label">
      Course
      <select class="wcof-select" name="course_id" id="course_id">
        <option disabled selected value> -- select an option -- </option>
        <option value="all">All</option>
        <?php foreach($products as $product): ?>
          <option value="<?php echo $product['id'] ?>"><?php echo $product['title'] ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label class="wcof-label">
      Registration Status
      <select class="wcof-select" name="status" id="status">
        <option disabled selected value> -- select an option -- </option>
        <option value="all">All</option>
        <?php foreach($statuses as $slug => $title): ?>
          <option value="<?php echo $slug ?>"><?php echo $title ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label class="wcof-label">
      Group
      <select class="wcof-select" name="group" id="group">
        <option disabled selected value> -- select an option -- </option>
        <option value="all">All</option>
        <?php foreach( $groups as $value => $label ): ?>
          <option value="<?php echo $value ?>"><?php echo $label ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label class="wcof-label">
      State
      <select class="wcof-select" name="state" id="state">
        <option disabled selected value> -- select an option -- </option>
        <option value="all">All</option>
        <?php foreach($states as $state): ?>
          <option value="<?php echo $state ?>"><?php echo $state ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label class="wcof-label">
      Region
      <select class="wcof-select" name="region" id="region">
        <option disabled selected value> -- select an option -- </option>
        <option value="all">All</option>
        <?php foreach( $regions as $value => $label ): ?>
          <option value="<?php echo $value ?>"><?php echo $label ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label class="wcof-label">
      Start Date
      <input autocomplete="off" name="start_date" type="text" placeholder="mm/dd/yyyy">
    </label>

    <label class="wcof-label">
      End Date
      <input autocomplete="off" name="end_date" type="text" placeholder="mm/dd/yyyy">
    </label>
    
    <div class="wcof-buttons-container">
      <button class="button" type="submit">Filter</button>
      <button class="wcof-clear-btn button" type="button">Clear All</button>
      <button class="wcof-export-btn button">Export</button>
    </div>
</div>

<script>
  const WCOF_AJAX_URL = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>