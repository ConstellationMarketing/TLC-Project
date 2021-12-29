jQuery(document).ready(function ($) {
  const urlParams = new URLSearchParams(window.location.search);
  const courseID = urlParams.get('course_id');
  const group = urlParams.get('group');
  const status = urlParams.get('status');
  const state = urlParams.get('state');
  const region = urlParams.get('region');
  const startDate = urlParams.get('start_date');
  const endDate = urlParams.get('end_date');

  $('[name="start_date"]').datepicker({
    dateFormat: 'mm/dd/yy'
  });
  $('[name="end_date"]').datepicker({
    dateFormat: 'mm/dd/yy'
  });

  if (courseID !== null) {
    $('select[name="course_id"]').val(courseID);
  }

  if (group !== null) {
    $('select[name="group"]').val(group);
  }

  if(status !== null) {
    $('select[name="status"]').val(status);
  }

  if(state !== null) {
    $('select[name="state"]').val(state);
  }

  if(region !== null) {
    $('select[name="region"]').val(region);
  }

  if(startDate) {
    $('input[name="start_date"]').val(startDate)
  }

  if(endDate) {
    $('input[name="end_date"]').val(endDate);
  }

  $('.wcof-form-container select').select2()

  $('.wcof-clear-btn').on('click', function () {
    $('select[name="_customer_user"]').val(null).trigger('change');

    $('.wcof-form-container select').each(function () {
      $(this).val(null).trigger('change');
    });

    $('.wcof-form-container input[type="text"]').each(function () {
      $(this).val('');
    });
  });

  $('.wcof-export-btn').on('click', function (e) {
    e.preventDefault();
    let _customer_user = $('select[name="_customer_user"]').val();
    let course_id = $('select[name="course_id"]').val();
    let group = $('select[name="group"]').val();
    let status = $('select[name="status"]').val();
    let state = $('select[name="state"]').val();
    let region = $('select[name="region"]').val();
    let start_date = $('input[name="start_date"]').val() || null;
    let end_date = $('input[name="end_date"]').val() || null;

    let data = {
      'wcof-export': 1,
      _customer_user,
      course_id,
      group,
      status,
      state,
      region,
      start_date,
      end_date
    };

    function isEmpty(value){
      return value == null || value == "";
    }

    for(key in data) {
      if(isEmpty(data[key])) {
        delete data[key]; 
      }
    }

    let queryString = new URLSearchParams(data).toString();
    let exportUrl = `/wp-admin?${queryString}`;
    window.open(exportUrl, '_blank');
  });
});