<style>
  .students-table-contact-btn {
    padding: 1px 6px;
    border-radius: 0 !important;
    text-transform: uppercase !important;
    transition: all .2s;
    font-size: 12px;
    line-height: 1.5;
    color: #fff;
    background-color: #0b3c43;
    border-color: #07282d;
    display: inline-block;
    margin-bottom: 0;
    font-weight: normal;
    text-align: center;
    vertical-align: middle;
    touch-action: manipulation;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    margin-right: 10px;
  }
  
  .username-cell {
    display: flex;
    align-items: center;
  }
</style>

<table style="visibility: hidden;" id="students-table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Practice Type</th>
      <th>City</th>
      <th>State</th>
      <th>Year Graduated</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($students as $student): ?>
      <?php $userdata = get_userdata($student->ID); ?>
      <tr data-id="<?php echo $student->ID; ?>">
        <td class="username-cell"><a class="students-table-contact-btn" href="/contact-student?user_id=<?php echo $student->ID ?>">CONTACT</a><?php echo $userdata->first_name ?> <?php echo $userdata->last_name ?></td>
        <td><?php the_field( 'ACF_practice_type', 'user_' . $student->ID ); ?></td>
        <td><?php echo get_user_meta( $student->ID, 'billing_city', true ); ?></td>
        <td><?php echo get_user_meta( $student->ID, 'billing_state', true ); ?></td>
        <td><?php the_field( 'ACF_graduation_year', 'user_' . $student->ID ); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  window.addEventListener('load', function () {
    jQuery(document).ready(function ($) {
      $('#students-table thead').append('<tr><th></th><th></th><th></th><th></th><th></th></tr>');
      $('#students-table thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" class="column_search" />' );
    } );
  
    // DataTable
    var table = $('#students-table').DataTable({
      orderCellsTop: true
    });
  
 
 
    $( '#students-table thead'  ).on( 'keyup', ".column_search",function () {
   
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    } );
      $('#students-table').css({visibility: 'visible'})
    })
  })
</script>