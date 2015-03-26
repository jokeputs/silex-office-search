$(function() {
    $('#search_query').focus();
    officeSearch.init();
});

var officeSearch = {
    form: null,

    init: function()
    {
        this.form = $('#search_query').closest('form');

        $(this.form).on('submit', function(e) {
            e.preventDefault();
            $('#alerts').empty();
            $('#offices').hide();
            officeSearch.getLocation();
        });
    },

    /**
     * Submit the search from
     */
    search: function()
    {
        var url = $(this.form).attr('action');
        var data = $(this.form).serializeArray();

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.success) {
                    officeSearch.showResults(data.offices);
                }
                else {
                    $.each(data.errors, function(index, error) {
                        officeSearch.showAlert(error, 'error');
                    });
                }
            }
        });
    },

    /**
     * Look up the latitude and longitude of the entered location
     */
    getLocation: function()
    {
        var input = $('#search_query').val();

        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json',
            data: {
                address: input,
                sensor: false,
                key: 'AIzaSyBJ2c_ZD8Bf6cfymMt_6t7uuQ03Jpzol3o'
            },
            dataType: 'json',
            success: function(data) {
                if (data.results.length) {
                    var location = data.results[0];

                    $('#search_latitude').val(location.geometry.location.lat);
                    $('#search_longitude').val(location.geometry.location.lng);

                    officeSearch.search();
                }
                else {
                    officeSearch.showAlert('Could not find the location', 'error');
                }
            }
        })
    },

    showResults: function(offices)
    {
        if (!offices.length) {
            this.showAlert('Could not find any offices', 'info')
            return;
        }

        $('#offices').empty();

        $.each(offices, function(index, office) {
            var li = '<li class="list-group-item">' + office + '</li>';
            $('#offices').append(li);
        });

        $('#offices').show();
    },

    showAlert: function(message, type)
    {
        var cssClass = type == 'error' ? 'alert-danger' : 'alert-info';
        var alert = '<div class="alert ' + cssClass + '">' + message + '</div>'
        $('#alerts').append(alert);
    }
}