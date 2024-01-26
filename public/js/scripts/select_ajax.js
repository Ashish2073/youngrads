$(document).ready(function () {

  //program ajax
  $('.program').select2({
    placeholder: 'What you want to study?',
    multiple: false,
    ajax: {
      url: `{{route('select-programs')}}`,
      dataType: 'json',
      type: 'POST',
      data: function (params) {
        return {
          name: params.term
        }
      },
      processResults: function (data) {
        return {
          results: data
        }
      }
    }
  });

  //country ajax
  $('.country_id').select2({
    placeholder: 'Where do you want to study?',
    multiple: false,
    ajax: {
      url: `{{route('get-countries')}}`,
      dataType: 'json',
      type: 'POST',
      data: function (params) {
        return {
          name: params.term
        }
      },
      processResults: function (data) {
        return {
          results: data
        }
      }
    }
  });

  //universtiy ajax
  $('.university').select2({
    placeholder: 'Type Unversity Name',
    ajax: {
      url: `{{route('select-university')}}`,
      dataType: 'json',
      type: 'POST',
      data: function (params) {
        return {
          name: params.term
        }
      },
      processResults: function (data) {
        return {
          results: data
        }
      }
    }
  });


});
