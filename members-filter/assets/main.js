jQuery(document).ready(function ($) {
  const urlParams = new URLSearchParams(window.location.search);
  const group = urlParams.get('group');
  const state = urlParams.get('state');
  const region = urlParams.get('region');
  const search = urlParams.get('search');

  if (group !== null) {
    $('select[name="group"]').val(group);
  }

  if(state !== null) {
    $('select[name="state"]').val(state);
  }

  if(region !== null) {
    $('select[name="region"]').val(region);
  }

  if(search !== null) {
    $('input#search').val(search);
  }
});